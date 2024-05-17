<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\DictamenOp;
use Illuminate\Support\Facades\Storage;

class OperacionController extends Controller
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
    public function index()
    {
        $dictamenes = DictamenOp::all();
        return view('armonia.operacion.index', compact('dictamenes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('armonia.operacion.crear');
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
        $NumDictamen->usuario_id  = $usuario->id;

        // Guardar la nueva entrada en la base de datos
        $NumDictamen->save();
        session()->flash('success', 'Dictamen creado exitosamente');
        $dictamenes = DictamenOp::all();
        return redirect()->route('operacion.index', compact('dictamenes'));
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
        return view('armonia.operacion.editar', compact('dictamen'));
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
        return redirect()->route('operacion.index', $id);
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
        $dictamen->save();

        // No se notifica ya que se tomara el valor de la tabla Notificar al administrador

        // Redireccionar con un mensaje de notificación
        return redirect()->route('operacion.index')->with('success', 'Solicitud de eliminación enviada para aprobación.');
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
        return redirect()->route('operacion.index')->with('success', 'Dictamen eliminado exitosamente');
    }
}
