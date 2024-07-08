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

class Servicio_Anexo_30Controller extends Controller
{

    protected $connection = 'segunda_db';

    function __construct()
    {
        $this->middleware('permission:ver-servicio_anexo_30|editar-servicio_anexo_30|borrar-servicio_anexo_30|crear-servicio_anexo_30', ['only' => ['index']]);
        $this->middleware('permission:ver-servicio_anexo_30', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-servicio_anexo_30', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-servicio_anexo_30', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        //Vista inicial de anexo 30 divida en tarjetas
        return view('armonia.servicio_anexo_30.index');
    }

    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function apro_servicio_anexo()
    {
        // Obtener el usuario autenticado
        $usuario = Auth::user();

        // Verificar si el usuario es administrador
        if (auth()->check() && $usuario->hasAnyRole(['Administrador', 'Auditor'])) {
            // Si es administrador, obtener todos los dictámenes
            $servicios = ServicioAnexo::all();

        } else {
            // Si no es administrador, obtener solo los dictámenes del usuario autenticado
            $servicios = ServicioAnexo::where('usuario_id', $usuario->id)->get();
        }

        // Pasar los dictámenes a la vista
        return view('armonia.servicio_anexo_30.aprobacion_servicio.index', compact('servicios'));
    }

    public function apro($id)
    {
        try {
            // Buscar el servicio por su ID
            $servicio = ServicioAnexo::findOrFail($id);

            // Establecer pending_apro_servicio como true
            $servicio->pending_apro_servicio = true;
            $servicio->save();

            // Redireccionar con un mensaje de éxito
            return redirect()->route('apro.anexo')->with('success', 'Servicio aprobado correctamente.');
        } catch (ModelNotFoundException $e) {
            // Manejar la excepción si el servicio no se encuentra
            return redirect()->route('apro.anexo') > with('error', 'Servicio no encontrado.');
        }
    }

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
        $html = view('armonia.servicio_anexo_30.cotizacion_pdf.cotizacion', compact('nombre_estacion', 'direccion_estacion', 'costo', 'iva', 'fecha_actual'))->render();
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

    public function descargarCotizacionAjax(Request $request)
    {
        // Obtener la ruta del documento desde la solicitud GET
        $rutaDocumento = $request->query('rutaDocumento');

        // Construir la ruta completa del archivo de cotización
        $rutaCompleta = public_path($rutaDocumento);

        // Verificar si el archivo existe
        if (!file_exists($rutaCompleta)) {
            abort(404, 'El archivo solicitado no existe.');
        }

        // Devolver el archivo como una respuesta binaria (blob)
        return response()->file($rutaCompleta);
    }

}
