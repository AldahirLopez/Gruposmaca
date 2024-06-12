<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\ServicioAnexo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;


use Illuminate\Support\Facades\Auth; // Importa la clase Auth

class ServicioAnexoController extends Controller
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
        // Obtener los IDs de los usuarios que tienen el rol "Verificador Anexo 30"
        $usuariosConRol = Role::on('mysql')->where('name', 'Verificador Anexo 30')->first()->users()->pluck('id');

        // Obtener los usuarios correspondientes a esos IDs
        $usuarios = User::on('mysql')->whereIn('id', $usuariosConRol)->get();

        // Obtener el usuario autenticado
        $usuario = Auth::user();

        // Verificar si se envió un usuario seleccionado en la solicitud
        $usuarioSeleccionado = $request->input('usuario_id');

        // Si se seleccionó un usuario, filtrar los servicios por ese usuario, de lo contrario, obtener todos los servicios
        if ($usuarioSeleccionado) {
            $servicios = ServicioAnexo::where('usuario_id', $usuarioSeleccionado)->get();
        } else {
            // Verificar si el usuario es administrador
            if (auth()->check() && $usuario->hasAnyRole(['Administrador', 'Auditor'])) {
                // Si es administrador, obtener todos los servicios
                $servicios = ServicioAnexo::all();
            } else {
                // Si no es administrador, obtener solo los servicios del usuario autenticado
                $servicios = ServicioAnexo::where('usuario_id', $usuario->id)->get();
            }
        }

        // Pasar los servicios y usuarios a la vista
        return view('armonia.anexo.servicio_anexo.index', compact('servicios', 'usuarios'));
    }

    public function obtenerServicios(Request $request)
    {
        // Obtener el usuario autenticado
        $usuario = Auth::user();

        // Obtener los IDs de los usuarios que tienen el rol "Verificador Anexo 30"
        $usuariosConRol = Role::on('mysql')->where('name', 'Verificador Anexo 30')->first()->users()->pluck('id');

        // Obtener los usuarios correspondientes a esos IDs
        $usuarios = User::on('mysql')->whereIn('id', $usuariosConRol)->get();

        // Verificar si se envió un usuario seleccionado en la solicitud
        $usuarioSeleccionado = $request->input('usuario_id');

        // Si se seleccionó un usuario, filtrar los servicios por ese usuario, de lo contrario, obtener todos los servicios
        if ($usuarioSeleccionado) {
            $servicios = ServicioAnexo::where('usuario_id', $usuarioSeleccionado)->get();
        } else {
            // Verificar si el usuario es administrador
            if (auth()->check() && $usuario->hasAnyRole(['Administrador', 'Auditor'])) {
                // Si es administrador, obtener todos los servicios
                $servicios = ServicioAnexo::all();
            } else {
                // Si no es administrador, obtener solo los servicios del usuario autenticado
                $servicios = ServicioAnexo::where('usuario_id', $usuario->id)->get();
            }
        }

        // Pasar los servicios a la vista
        return view('partials.tabla_servicios', compact('servicios', 'usuarios'));
    }


    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Lista de estados de México
        $estados = [
            'Aguascalientes',
            'Baja California',
            'Baja California Sur',
            'Campeche',
            'Chiapas',
            'Chihuahua',
            'Coahuila',
            'Colima',
            'Ciudad de México',
            'Durango',
            'Guanajuato',
            'Guerrero',
            'Hidalgo',
            'Jalisco',
            'México',
            'Michoacán',
            'Morelos',
            'Nayarit',
            'Nuevo León',
            'Oaxaca',
            'Puebla',
            'Querétaro',
            'Quintana Roo',
            'San Luis Potosí',
            'Sinaloa',
            'Sonora',
            'Tabasco',
            'Tamaulipas',
            'Tlaxcala',
            'Veracruz',
            'Yucatán',
            'Zacatecas'
        ];
        return view('armonia.anexo.servicio_anexo.crear', compact('estados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required',
            'direccion' => 'required',
            'estado' => 'required',
        ]);

        $usuario = Auth::user(); // O el método que uses para obtener el usuario
        $nomenclatura = $this->generarNomenclatura($usuario);

        $servicio = new ServicioAnexo();

        // Establecer los valores de los campos

        $servicio->nombre_estacion = $request->nombre;
        $servicio->direccion_estacion = $request->direccion;
        $servicio->estado_estacion = $request->estado;
        $servicio->nomenclatura = $nomenclatura;
        $servicio->estado = false;
        $servicio->usuario_id = $usuario->id;



        // Asigna otros campos al servicio

        $servicio->save();

        // Definir la carpeta de destino dentro de 'public/storage'
        $customFolderPath = "servicios_anexo30/{$nomenclatura}";

        // Crear la carpeta si no existe
        Storage::disk('public')->makeDirectory($customFolderPath);

        return redirect()->route('servicio_anexo.index')->with('success', 'servicio creado exitosamente');
        ;
    }

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
    public function destroy(string $id)
    {
        //Aca lo que se hara sera mandar el pendiente de borrar a la tabla  de Dictamenop y luego se tiene que notiicar al usuari
        //administrador que tiene una notificacion pendiente de aprobar para poder eliminar el registro
        // Buscar el dictamen por su ID
        //Obetner si es administrador y si si borrarlo de una si no solo lanar el pendiente
        $servicio = ServicioAnexo::findOrFail($id);

        // Marcar el dictamen como pendiente de eliminación
        $servicio->pending_deletion = true;

        // Obtener la fecha y hora actuales
        $fechaHoraActual = Carbon::now();

        // Formatear la fecha y la hora según tu preferencia
        $fechaHoraFormateada = $fechaHoraActual->format('Y-m-d H:i:s');

        // Asignar la fecha y hora formateadas al modelo
        $servicio->eliminated_at = $fechaHoraFormateada;

        $servicio->save();

        // No se notifica ya que se tomara el valor de la tabla Notificar al administrador

        // Redireccionar con un mensaje de notificación
        return redirect()->route('servicio_anexo.index')->with('success', 'Solicitud de eliminación enviada para aprobación.');
    }

    public function generarNomenclatura($usuario)
    {
        $iniciales = $this->obtenerIniciales($usuario);
        $anio = date('Y');
        $nomenclatura = '';
        $numero = 1;

        do {
            $nomenclatura = "A-$iniciales-$numero-$anio";
            $existe = ServicioAnexo::where('nomenclatura', $nomenclatura)->exists();

            if ($existe) {
                $numero++;
            } else {
                break;
            }
        } while (true);

        return $nomenclatura;
    }

    private function obtenerIniciales($usuario)
    {
        $nombres = explode(' ', $usuario->name); // Suponiendo que el campo de nombres es 'name'
        $iniciales = '';
        $contador = 0;

        foreach ($nombres as $nombre) {
            if ($contador < 3) {
                $iniciales .= substr($nombre, 0, 1);
                $contador++;
            } else {
                break;
            }
        }

        return strtoupper($iniciales);
    }

    public function AproAnexo()
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
        return view('armonia.anexo.cotizacion.apro_anexo', compact('servicios'));
    }


    //Metodo para generar el pdf de la cotizacion
    public function generarpdfcotizacion(Request $request)
    {
        // Establecer la configuración regional en español
        app()->setLocale('es');

        // Obtener la información del servicio y los datos del formulario
        $nomenclatura = $request->input('nomenclatura');
        $nombre_estacion = $request->input('nombre_estacion');
        $direccion_estacion = $request->input('direccion_estacion');
        $estado_estacion = $request->input('estado_estacion');
        $costo = $request->input('costo');

        // Calcular el 16% de IVA
        $iva = $costo * 0.16;

        // Obtener la fecha actual en el formato deseado (día de mes de año)
        $fecha_actual = Carbon::now()->formatLocalized('%A %d de %B de %Y');

        // Ruta de la carpeta donde se guardarán los PDFs
        $folderPath = "public/servicios_anexo30/{$nomenclatura}";
        $pdfPath = "{$folderPath}/cotizacion/{$nomenclatura}.pdf"; // Ruta completa del PDF

        

        // Verificar si la carpeta existe, si no, crearla
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath);
        }

        // Pasar los datos al PDF y renderizarlo, incluyendo la fecha actual
        $html = view('armonia.anexo.cotizacion.cotizacion_pdf.cotizacion', compact('nombre_estacion', 'direccion_estacion', 'estado_estacion', 'costo', 'iva', 'fecha_actual'))->render();
        $pdf = PDF::loadHTML($html);

        // Guardar el PDF en el almacenamiento de Laravel
        Storage::put($pdfPath, $pdf->output());

        // Obtener la URL pública del PDF
        $pdfUrl = Storage::url($pdfPath);

        // Devolver la URL del PDF como respuesta
        return response()->json(['pdf_url' => $pdfUrl]);
    }

}
