<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Cotizacion_Servicio_Anexo30;
use App\Models\ServicioAnexo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;


use Illuminate\Support\Facades\Auth; // Importa la clase Auth

class ServicioAnexo30PruebaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $connection = 'segunda_db';

    function __construct()
    {
        $this->middleware('permission:ver-servicio|crear-servicio|editar-servicio|borrar-servicio', ['only' => ['index']]);
        $this->middleware('permission:crear-servicio', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-servicio', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-servicio', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        // Inicializar colecciones y variables necesarias
        $usuarios = collect();
        $servicios = collect();
        $warnings = []; 

        // Obtener el rol "Verificador Anexo 30"
        $rolVerificador = Role::on('mysql')->where('name', 'Verificador Anexo 30')->first();

        // Verificar si el rol existe y obtener los usuarios asociados
        if ($rolVerificador) {
            // Obtener los IDs de los usuarios que tienen el rol "Verificador Anexo 30"
            $usuariosConRol = $rolVerificador->users()->pluck('id');

            // Si hay usuarios con el rol, obtenerlos
            if ($usuariosConRol->isNotEmpty()) {
                $usuarios = User::on('mysql')->whereIn('id', $usuariosConRol)->get();
            }
        }

        // Verificar si el usuario está autenticado
        $usuario = Auth::user();

        if ($usuario) {
            // Verificar si se envió un usuario seleccionado en la solicitud
            $usuarioSeleccionado = $request->input('usuario_id');

            // Si se seleccionó un usuario, filtrar los servicios por ese usuario, de lo contrario, obtener todos los servicios
            if ($usuarioSeleccionado) {
                $servicios = ServicioAnexo::where('usuario_id', $usuarioSeleccionado)->get();
            } else {
                // Verificar si el usuario es administrador
                if ($usuario->hasAnyRole(['Administrador', 'Auditor'])) {
                    // Si es administrador, obtener todos los servicios
                    $servicios = ServicioAnexo::all();
                } else {
                    // Si no es administrador, obtener solo los servicios del usuario autenticado
                    $servicios = ServicioAnexo::where('usuario_id', $usuario->id)->get();
                }
            }
        }

        // Siempre retornar la vista, incluso si no se encuentran usuarios o servicios
        return view('armonia.anexo.servicio_anexo.index', compact('servicios', 'usuarios'));
    }


   

    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }
    /**
     * Show the form for creating a new resource.
     */
    

    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    

    

    

    //Metodo para generar el pdf de la cotizacion
    public function generarpdfcotizacion(Request $request)
    {
        // Establecer la configuración regional en español
        app()->setLocale('es');

        // Obtener los datos del formulario
        $id_servicio = $request->input('id_servicio');
        $nomenclatura = $request->input('nomenclatura');
        $nombre_estacion = strtoupper($request->input('razon_social'));
        $direccion_estacion = strtoupper($request->input('direccion'));
        $costo = $request->input('costo');

        // Calcular el 16% de IVA 
        $iva = $costo * 0.16;

        // Obtener la fecha actual en el formato deseado (día de mes de año)
        $fecha_actual = Carbon::now()->formatLocalized('%A %d de %B de %Y');

        // Definir la carpeta principal y la subcarpeta donde se guardarán los PDFs
        $folderPath = "servicios_anexo30/{$nomenclatura}";
        $subFolderPath = "{$folderPath}/cotizacion"; 

        // Verificar y crear la carpeta principal si no existe
        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        // Verificar y crear la subcarpeta dentro de la carpeta principal si no existe
        if (!Storage::disk('public')->exists($subFolderPath)) {
            Storage::disk('public')->makeDirectory($subFolderPath);
        }

        // Definir la ruta completa del PDF
        $pdfPath = "{$subFolderPath}/Cotizacion_{$nomenclatura}.pdf";

        // Pasar los datos al PDF y renderizarlo, incluyendo la fecha actual
        $html = view('armonia.anexo.cotizacion.cotizacion_pdf.cotizacion', compact('nombre_estacion', 'direccion_estacion', 'costo', 'iva', 'fecha_actual'))->render();
        $pdf = PDF::loadHTML($html);

        // Guardar el PDF en el almacenamiento de Laravel
        Storage::disk('public')->put($pdfPath, $pdf->output());

        // Obtener la URL pública del PDF
        $pdfUrl = Storage::url($pdfPath);

        // Verificar si ya existe una cotización con este id_servicio
        $cotizacion = Cotizacion_Servicio_Anexo30::where('servicio_anexo_id', $id_servicio)->first();

        if ($cotizacion) {
            // Si ya existe, actualiza el registro existente
            $cotizacion->rutadoc_cotizacion = $pdfUrl;
            $cotizacion->save();
        } else {
            // Si no existe, crea un nuevo registro
            $cotizacion = new Cotizacion_Servicio_Anexo30();
            $cotizacion->rutadoc_cotizacion = $pdfUrl;
            $cotizacion->servicio_anexo_id = $id_servicio;
            $cotizacion->estado_cotizacion = true;
            // Asigna otros campos si es necesario
            $cotizacion->save();
        }

        // Devolver la URL del PDF como respuesta
        return response()->json(['pdf_url' => $pdfUrl]);
    }


    public function mostrarCotizacion($servicio_id)
    {
        // Encuentra la cotización por ID
        $cotizacion_servicio = Cotizacion_Servicio_Anexo30::findOrFail($servicio_id);

        // Verifica si la ruta del PDF está presente
        if (!$cotizacion_servicio->rutadoc_cotizacion) {
            return redirect()->back()->withErrors('No se encontró la ruta del PDF para el servicio especificado.');
        }

        // Obtiene la ruta completa del archivo PDF
        $pdf_path = storage_path('app/' . $cotizacion_servicio->rutadoc_cotizacion);

        // Verifica si el archivo existe
        if (!file_exists($pdf_path)) {
            return redirect()->back()->withErrors('El archivo PDF no existe en la ruta especificada.');
        }

        // Retorna el archivo para mostrarlo en el navegador
        return response()->file($pdf_path);
    }


    


}
