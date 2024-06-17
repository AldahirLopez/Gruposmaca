<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\DictamenOp;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Auth; // Importa la clase Auth

use Illuminate\Support\Carbon;

class ServicioOperacionController extends Controller
{
    protected $connection = 'segunda_db';

    function __construct()
    {
        $this->middleware('permission:ver-operacion|crear-operacion|editar-operacion|borrar-operacion', ['only' => ['index']]);
        $this->middleware('permission:crear-operacion', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-operacion', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-operacion', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */

    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function index()
    {
        // Obtener el usuario autenticado
        $usuario = Auth::user();

        // Verificar si el usuario es administrador
        if (auth()->check() && $usuario->hasAnyRole(['Administrador', 'Auditor'])) {
            // Si es administrador o auditor, obtener todos los dictámenes
            $dictamenes = DictamenOp::all();
        } else {
            // Si no es administrador o auditor, obtener solo los dictámenes del usuario autenticado
            $dictamenes = DictamenOp::where('usuario_id', $usuario->id)->get();
        }

        // Pasar los dictámenes a la vista
        return view('armonia.operacion.servicio_operacion.index', compact('dictamenes'));
    }

    /**
     * Show the form for creating a new resource.
     */

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('armonia.operacion.servicio_operacion.crear');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'numero_dictamen' => 'required',
        ]);

        //Obtener el Usuario logeado 
        $usuario = auth()->user();

        // Crear una nueva instancia del modelo Obras
        $NumDictamen = new DictamenOp();

        // Establecer los valores de los campos
        $NumDictamen->nombre = $request->numero_dictamen;
        $NumDictamen->usuario_id = $usuario->id;

        // Guardar la nueva entrada en la base de datos
        $NumDictamen->save();

        // Definir la carpeta de destino dentro de 'public/storage'
        $customFolderPath = "NOM-005/{$request->numero_dictamen}";

        // Crear la carpeta si no existe
        Storage::disk('public')->makeDirectory($customFolderPath);

        session()->flash('success', 'Dictamen creado exitosamente');
        $dictamenes = DictamenOp::all();
        return redirect()->route('servicio_operacion.index', compact('dictamenes'));
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
        $dictamen = DictamenOp::findOrFail($id);

        // Marcar el dictamen como pendiente de eliminación
        $dictamen->pending_deletion = true;
        // Obtener la fecha y hora actuales
        $fechaHoraActual = Carbon::now();

        // Formatear la fecha y la hora según tu preferencia
        $fechaHoraFormateada = $fechaHoraActual->format('Y-m-d H:i:s');

        // Asignar la fecha y hora formateadas al modelo
        $dictamen->eliminated_at = $fechaHoraFormateada;
        $dictamen->save();

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
        return redirect()->route('armonia.operacion.servicio_operacion.index')->with('success', 'Dictamen eliminado exitosamente');
    }
}
