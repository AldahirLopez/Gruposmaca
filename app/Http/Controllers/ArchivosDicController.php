<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ArchivosOp;
use Illuminate\Http\Request;

class ArchivosDicController extends Controller
{

    protected $connection = 'segunda_db';

    function __construct()
    {
        $this->middleware('permission:ver-archivos|crear-archivos|editar-archivos|borrar-archivos', ['only' => ['index']]);
        $this->middleware('permission:crear-archivos', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-archivos', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-archivos', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener el ID del dictamen de la URL
        $dictamen_id = $request->dictamen_id;

        // Obtener los archivos relacionados con el dictamen y el usuario logueado
        $archivos = ArchivosOp::where('numdicop_id', $dictamen_id)
            ->paginate(5);

        // Pasar el usuario, los archivos y el ID del dictamen a la vista
        return view('armonia.archivos.index', [
            'archivos' => $archivos,
            'dictamen_id' => $dictamen_id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Obtener el usuario autenticado actualmente
        $usuario = auth()->user();
        // Obtener el dictamen_id de la solicitud (si se pasó)
        $dictamen_id = $request->dictamen_id;
        // Pasar el usuario y el dictamen_id a la vista
        return view('armonia.archivos.crear', compact('usuario', 'dictamen_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Obtener el ID del dictamen de la URL
        $dictamenId = $request->dictamen_id;

        // Obtener el ID del usuario autenticado
        $usuarioId = auth()->id();

        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required',
            'archivo' => 'required|file', // Validar que se haya subido un archivo
        ]);

        // Crear una nueva instancia del modelo Archivos
        $archivo = new ArchivosOp();
        $archivo->nombre = $request->input('nombre'); // Asignar el nombre
        // Guardar el archivo en el sistema de archivos
        $archivoSubido = $request->file('archivo');
        $rutaArchivo = $archivoSubido->store('public/archivos');
        $archivo->rutadoc = str_replace('public/', '', $rutaArchivo); // Guardar la ruta del archivo en la base de datos

        // Asignar el dictamen_id obtenido de la URL
        $archivo->numdicop_id = $dictamenId;

        // Guardar la nueva entrada en la base de datos
        $archivo->save();

        // Redirigir al usuario a la página de lista de archivos con el dictamen_id en la URL
        return redirect()->route('archivos.index', ['dictamen_id' => $dictamenId])->with('success', 'Documento creado exitosamente');
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
        // Buscar el plano por su ID
        $archivo = ArchivosOp::findOrFail($id);


        // Obtener la ruta del archivo asociado al plano
        $rutaArchivo = storage_path('app/public/' . $archivo->rutadoc);

        // Verificar si el archivo existe y eliminarlo
        if (file_exists($rutaArchivo)) {
            unlink($rutaArchivo); // Eliminar el archivo del sistema de archivos
        }

        // Eliminar el plano de la base de datos
        $archivo->delete();

        // Redirigir al usuario a la página de lista de planos
        return redirect()->route('archivos.index', ['dictamen_id' => $archivo->numdicop_id ])->with('success', 'Documento eliminado exitosamente');
    }
}
