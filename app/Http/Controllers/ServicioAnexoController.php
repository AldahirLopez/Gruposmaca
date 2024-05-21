<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\ServicioAnexo;
use Illuminate\Http\Request;

use Illuminate\Support\Carbon;

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

    public function index()
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
        return view('armonia.servicio_anexo.index', compact('servicios'));
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
        return view('armonia.servicio_anexo.crear');
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
        ]);

        $usuario = Auth::user(); // O el método que uses para obtener el usuario
        $nomenclatura = $this->generarNomenclatura($usuario);

        $servicio = new ServicioAnexo();

        // Establecer los valores de los campos

        $servicio->nombre_estacion = $request->nombre;
        $servicio->direccion_estacion = $request->direccion;
        $servicio->nomenclatura = $nomenclatura;
        $servicio->estado = false;;
        $servicio->usuario_id = $usuario->id;;


        // Asigna otros campos al servicio
        $servicio->save();
        return redirect()->route('servicio_anexo.index')->with('success', 'servicio creado exitosamente');;
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
}
