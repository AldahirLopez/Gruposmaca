<?php

namespace App\Http\Controllers;

use App\Models\Datos_Servicio;
use App\Models\Estacion;
use App\Models\Estacion_Servicio;
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
use Illuminate\Support\Facades\DB;
use App\Models\Usuario_Estacion;


use Illuminate\Support\Facades\Auth; // Importa la clase Auth

class Servicio_Inspector_Anexo_30Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $connection = 'segunda_db';

    function __construct()
    {
        $this->middleware('permission:ver-servicio_anexo_30|editar-servicio_anexo_30|borrar-servicio_anexo_30|crear-servicio_anexo_30', ['only' => ['index']]);
        $this->middleware('permission:crear-servicio_anexo_30', ['only' => ['create', 'store']]);   
        $this->middleware('permission:borrar-servicio_anexo_30', ['only' => ['destroy']]);
       

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
                    $estaciones = Estacion::all();
                } else {
                    // Si no es administrador, obtener solo los servicios del usuario autenticado
                    $servicios = ServicioAnexo::where('usuario_id', $usuario->id)->get();
                    $estacionesDirectas = Estacion::where('usuario_id', $usuario->id)->get();
                    // Inicializar una colección para las estaciones relacionadas
                    $estacionesRelacionadas = collect();

                    // Verificar si el usuario no es administrador para buscar relaciones
                    if (!$usuario->hasAnyRole(['Administrador', 'Auditor'])) {
                        // Obtener las relaciones de usuario a estación
                        $relaciones = Usuario_Estacion::where('usuario_id', $usuario->id)->get();

                        // Recorrer las relaciones para obtener las estaciones relacionadas
                        foreach ($relaciones as $relacion) {
                            // Obtener la estación relacionada y añadirla a la colección
                            $estacionRelacionada = Estacion::find($relacion->estacion_id);
                            if ($estacionRelacionada) {
                                $estacionesRelacionadas->push($estacionRelacionada);
                            }
                        }
                    }
                    // Combinar estaciones directas y relacionadas y eliminar duplicados
                    $estaciones = $estacionesDirectas->merge($estacionesRelacionadas)->unique('id');
                }
            }
        }


        // Siempre retornar la vista, incluso si no se encuentran usuarios o servicios
        return view('armonia.servicio_anexo_30.datos_servicio_anexo.index', compact('servicios', 'usuarios', 'estaciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $estacionId = $request->input('estacion');

        $usuario = Auth::user(); // O el método que uses para obtener el usuario
        $nomenclatura = $this->generarNomenclatura($usuario);

        // Crear instancia de ServicioAnexo y guardar datos
        $servicio = new ServicioAnexo();
        $servicio->nomenclatura = $nomenclatura;
        $servicio->pending_apro_servicio = false;
        $servicio->pending_deletion_servicio = false;
        $servicio->usuario_id = $usuario->id;
        // Asigna otros campos al servicio según sea necesario
        $servicio->save();

        // Obtener el ID del servicio anexo creado
        $servicio_anexo_id = $servicio->id;

        // Crear instancia de Estacion_Servicio y guardar la relación
        $estacionServicio = new Estacion_Servicio();
        $estacionServicio->servicio_anexo_id = $servicio_anexo_id;
        $estacionServicio->estacion_id = $estacionId;
        // Asigna otros campos a Estacion_Servicio si es necesario
        $estacionServicio->save();

        // Definir la carpeta de destino dentro de 'public/storage'
        $customFolderPath = "Servicios_Anexo30/{$nomenclatura}";

        // Crear la carpeta si no existe
        Storage::disk('public')->makeDirectory($customFolderPath);

        return redirect()->route('servicio_inspector_anexo_30.index')->with('success', 'Servicio creado exitosamente');

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

    public function expediente_anexo30(Request $request)
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

        // Verificar si el usuario está autenticado
        $usuario = Auth::user();
        if ($usuario->hasAnyRole(['Administrador', 'Auditor'])) {

            //Si es administrador o auditor puede ver todo y editar todo 
            $servicio_anexo_id = $request->servicio_anexo_id;
            $estacion = ServicioAnexo::find($servicio_anexo_id);
           // $archivoAnexo = Datos_Servicio::where('servicio_anexo_id', $servicio_anexo_id)->first();

            // Ruta de la carpeta donde se guardan los archivos generados
            $folderPath = "Servicios_Anexo30/{$estacion->nomenclatura}/formatos_rellenados_anexo30";
            $existingFiles = [];

            // Verificar si la carpeta existe
            if (Storage::disk('public')->exists($folderPath)) {
                // Obtener los archivos existentes en la carpeta
                $files = Storage::disk('public')->files($folderPath);

                // Construir la lista de archivos con su URL
                foreach ($files as $file) {
                    $existingFiles[] = [
                        'name' => basename($file),
                        'url' => Storage::url($file)
                    ];
                }
            }

            return view('armonia.anexo.servicio_anexo.datos_servicio_anexo.expediente', compact('estados', 'servicio_anexo_id', 'estacion', 'existingFiles'));

        } else {

            //Si es administrador o auditor puede ver todo y editar todo 
            $servicio_anexo_id = $request->servicio_anexo_id;
            $estacion = ServicioAnexo::find($servicio_anexo_id);
            $validar_servicio = ($estacion->usuario_id == $usuario->id);
            //$validar_usuario =  User::where('usuario_id',)

            if ($validar_servicio) {
              //  $archivoAnexo = Datos_Servicio::where('servicio_anexo_id', $servicio_anexo_id)->first();
                //Si es administrador o auditor puede ver todo y editar todo 
                $servicio_anexo_id = $request->servicio_anexo_id;
                $estacion = ServicioAnexo::find($servicio_anexo_id);
                //$archivoAnexo = Datos_Servicio::where('servicio_anexo_id', $servicio_anexo_id)->first();

                // Ruta de la carpeta donde se guardan los archivos generados
                $folderPath = "Servicios_Anexo30/{$estacion->nomenclatura}/formatos_rellenados_anexo30";
                $existingFiles = [];

                // Verificar si la carpeta existe
                if (Storage::disk('public')->exists($folderPath)) {
                    // Obtener los archivos existentes en la carpeta
                    $files = Storage::disk('public')->files($folderPath);

                    // Construir la lista de archivos con su URL
                    foreach ($files as $file) {
                        $existingFiles[] = [
                            'name' => basename($file),
                            'url' => Storage::url($file)
                        ];
                    }
                }
                return view('armonia.anexo.servicio_anexo.datos_servicio_anexo.expediente', compact('estados', 'servicio_anexo_id', 'estacion', 'existingFiles'));
            } else {

                return redirect()->route('servicio_anexo.index')->with('error', 'Servicio no valido');
            }

        }


    }

    public function destroy(string $id)
    {
        //Aca lo que se hara sera mandar el pendiente de borrar a la tabla  de Dictamenop y luego se tiene que notiicar al usuari
        //administrador que tiene una notificacion pendiente de aprobar para poder eliminar el registro
        // Buscar el dictamen por su ID
        //Obetner si es administrador y si si borrarlo de una si no solo lanar el pendiente
        $servicio = ServicioAnexo::findOrFail($id);

        // Marcar el dictamen como pendiente de eliminación
        $servicio->pending_deletion_servicio = true;

        // Obtener la fecha y hora actuales
        $fechaHoraActual = Carbon::now();

        // Formatear la fecha y la hora según tu preferencia
        $fechaHoraFormateada = $fechaHoraActual->format('Y-m-d H:i:s');

        // Asignar la fecha y hora formateadas al modelo
        $servicio->date_eliminated_at = $fechaHoraFormateada;

        $servicio->save();

        // No se notifica ya que se tomara el valor de la tabla Notificar al administrador

        // Redireccionar con un mensaje de notificación
        return redirect()->route('servicio_inspector_anexo_30.index')->with('success', 'Solicitud de eliminación enviada para aprobación.');
    }

    //Obtener Servicios por inspector
    public function obtenerServicios(Request $request)
    {
        $usuarioSeleccionado = $request->input('usuario_id');
        $estadoSeleccionado=$request->input('estado');
        $yearSeleccionado=$request->input('year');
      
    
            // Obtener el usuario autenticado
            $usuario = Auth::user();

            // Obtener el rol "Verificador Anexo 30"
            $rol = Role::on('mysql')->where('name', 'Verificador Anexo 30')->first();

            if (!$rol) {
                throw new \Exception('El rol "Verificador Anexo 30" no existe.');
            }

            // Obtener los IDs de los usuarios con el rol específico
            $usuariosConRol = $rol->users()->pluck('id');

            if ($usuariosConRol->isEmpty()) {
                throw new \Exception('No hay usuarios con el rol "Verificador Anexo 30".');
            }

            // Obtener los usuarios correspondientes a esos IDs
            $usuarios = User::on('mysql')->whereIn('id', $usuariosConRol)->get();

            if($usuarioSeleccionado==="todos"){
                $servicios=ServicioAnexo::all();
            }
            $usuario=User::find($usuarioSeleccionado);
         
            $servicios = ServicioAnexo::query()
            ->join('estacion_servicio', 'servicio_anexo_30.id', '=', 'estacion_servicio.servicio_anexo_id')
            ->join('estacion', 'estacion.id', '=', 'estacion_servicio.estacion_id')
            ->select('servicio_anexo_30.*')
            ->when($usuarioSeleccionado != 'todos', function ($query) use ($usuarioSeleccionado) {
                return $query->where('servicio_anexo_30.usuario_id', $usuarioSeleccionado);
            })
            ->when($yearSeleccionado, function ($query) use ($yearSeleccionado) {
                return $query->whereYear('servicio_anexo_30.created_at', $yearSeleccionado);
            })
            ->when($estadoSeleccionado, function ($query) use ($estadoSeleccionado) {
                return $query->where('estacion.estado_republica_estacion', $estadoSeleccionado);
            })
            ->get();
          

           
            // Pasar los datos a la vista
           return redirect()->route('servicio_inspector_anexo_30.index')->with(['servicios' => $servicios,'año'=>$yearSeleccionado,'estado'=>$estadoSeleccionado,'usuario'=>$usuario]);
          

        
    }



}