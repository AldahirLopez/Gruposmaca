<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Estacion;
use App\Models\Estacion_Operacion;
use App\Models\ServicioOperacion;
use App\Models\Usuario_Estacion;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\DictamenOp;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth; // Importa la clase Auth

use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;

//Este controlador se va utilizar para la parte cliente donde va ser sus servicios y poder subir sus archivos

class ServicioOperacionController extends Controller
{
    protected $connection = 'segunda_db';

    function __construct()
    {
        $this->middleware('permission:ver-servicio_operacion_mantenimiento|crear-servicio_operacion_mantenimiento|borrar-servicio_operacion_mantenimiento', ['only' => ['index']]);
        $this->middleware('permission:crear-servicio_operacion_mantenimiento', ['only' => ['create', 'store']]);
        $this->middleware('permission:borrar-servicio_operacion_mantenimiento', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */

    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function index(Request $request)
    {
        // Inicializar colecciones y variables necesarias
        $usuarios = collect();
        $servicios = collect();
        $warnings = [];

        // Obtener el rol "Verificador Anexo 30"
        $rolVerificador = Role::on('mysql')->where('name', 'Operacion y Mantenimiento')->first();

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
                $servicios = ServicioOperacion::where('usuario_id', $usuarioSeleccionado)->get();
            } else {
                // Verificar si el usuario es administrador
                if ($usuario->hasAnyRole(['Administrador'])) {
                    // Si es administrador, obtener todos los servicios
                    $servicios = ServicioOperacion::all();
                    $estaciones = Estacion::all();
                } else {
                    // Si no es administrador, obtener solo los servicios del usuario autenticado
                    $servicios = ServicioOperacion::where('usuario_id', $usuario->id)->get();
                    $estacionesDirectas = Estacion::where('usuario_id', $usuario->id)->get();
                    // Inicializar una colección para las estaciones relacionadas
                    $estacionesRelacionadas = collect();

                    // Verificar si el usuario no es administrador para buscar relaciones
                    if (!$usuario->hasAnyRole(['Administrador', 'Operacion y Mantenimiento'])) {
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
       return view('armonia.operacion.servicio_operacion.index', compact('servicios', 'usuarios', 'estaciones'));
    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

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
        $servicio = new ServicioOperacion();
        $servicio->nomenclatura = $nomenclatura;
        $servicio->pending_apro_servicio = false;
        $servicio->pending_deletion_servicio = false;
        $servicio->usuario_id = $usuario->id;
        // Asigna otros campos al servicio según sea necesario
        $servicio->save();

        // Obtener el ID del servicio anexo creado
        $servicio_op_id = $servicio->id;

        // Crear instancia de Estacion_Servicio y guardar la relación
        $estacionServicio = new Estacion_Operacion();
        $estacionServicio->servicio_operacion_id = $servicio_op_id;
        $estacionServicio->estacion_id = $estacionId;
        // Asigna otros campos a Estacion_Servicio si es necesario
        $estacionServicio->save();

        // Definir la carpeta de destino dentro de 'public/storage'
        $customFolderPath = "OperacionyMantenimiento/{$nomenclatura}";

        // Crear la carpeta si no existe
        Storage::disk('public')->makeDirectory($customFolderPath);

        return redirect()->route('servicio_operacion.index')->with('success', 'Servicio creado exitosamente');
    }

    public function generarNomenclatura($usuario)
    {
        $iniciales = $this->obtenerIniciales($usuario);
        $anio = date('Y');
        $nomenclatura = '';
        $numero = 1;

        do {
            $nomenclatura = "OM-$iniciales-$numero-$anio";
            $existe = ServicioOperacion::where('nomenclatura', $nomenclatura)->exists();

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
        $dictamen = DictamenOp::find($id);
        return view('armonia.operacion.servicio_operacion.editar', compact('dictamen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required',
        ]);

        // Buscar la obra por su ID y actualizar los datos
        $NumDictamen = DictamenOp::findOrFail($id);
        $NumDictamen->update([
            'nombre' => $request->nombre,
            // Aquí puedes añadir más campos que necesites actualizar
        ]);

        // Agregar el mensaje de éxito a la sesión
        session()->flash('success', 'El dictamen ha sido actualizado exitosamente');
        // Redirigir a la vista de edición de la obra
        return redirect()->route('servicio_operacion.index', $id);
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
        $servicio_operacion = ServicioOperacion::findOrFail($id);

        // Marcar el dictamen como pendiente de eliminación
        $servicio_operacion->pending_deletion_servicio = true;
        // Obtener la fecha y hora actuales
        $fechaHoraActual = Carbon::now();

        // Formatear la fecha y la hora según tu preferencia
        $fechaHoraFormateada = $fechaHoraActual->format('Y-m-d H:i:s');

        // Asignar la fecha y hora formateadas al modelo
        $servicio_operacion->date_eliminated_at = $fechaHoraFormateada;
        $servicio_operacion->save();

        // No se notifica ya que se tomara el valor de la tabla Notificar al administrador

        // Redireccionar con un mensaje de notificación
        return redirect()->route('servicio_operacion.index')->with('success', 'Solicitud de eliminación enviada para aprobación.');
    }
    public function listararchivos(string $id)
    {

        // Buscar el dictamen por su ID
        $dictamen = DictamenOp::findOrFail($id);

        // Obtener los archivos relacionados
        $archivos = $dictamen->dicarchivos;

        // Eliminar los archivos del sistema de archivos
        foreach ($archivos as $archivo) {
            Storage::delete('public/' . $archivo->rutadoc);
        }

        // Eliminar los registros relacionados en dicarchivos
        $dictamen->dicarchivos()->delete();

        // Eliminar el dictamen
        $dictamen->delete();

        // Redireccionar con un mensaje de éxito
        return redirect()->route('armonia.operacion.index')->with('success', 'Dictamen eliminado exitosamente');
    }

    //PARTE DE LAS APROBACIONES DE LOS SERVICIOS DE OPERACION Y MANTENIMIENTO

   


}
