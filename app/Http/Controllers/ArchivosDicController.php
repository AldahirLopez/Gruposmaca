<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ArchivosOp;
use App\Models\DictamenOp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


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

        // Obtener el nombre del dictamen
        $dictamen = DictamenOp::findOrFail($dictamen_id)->nombre;

        // Obtener los archivos relacionados con el dictamen y el usuario logueado
        $archivos = ArchivosOp::where('numdicop_id', $dictamen_id)
            ->paginate(5);

        // Contar la cantidad de archivos asociados al número de dictamen
        $cantidadArchivos = ArchivosOp::where('numdicop_id', $dictamen_id)->count();

        // Pasar el nombre del dictamen, los archivos y el ID del dictamen a la vista
        return view('armonia.operacion.archivos.index', [
            'archivos' => $archivos,
            'dictamen' => $dictamen,
            'dictamen_id' => $dictamen_id,
            'cantidadArchivos' => $cantidadArchivos
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

        // Contar la cantidad de archivos asociados al número de dictamen
        $cantidadArchivos = ArchivosOp::where('numdicop_id', $dictamen_id)->count();

        // Verificar si ya hay ocho archivos asociados
        if ($cantidadArchivos >= 8) {
            return redirect()->route('armonia.operacion.archivos.index', ['dictamen_id' => $dictamen_id])->with('error', 'No se pueden agregar más archivos. Se alcanzó el límite de ocho archivos.');
        }

        // Pasar el usuario y el dictamen_id a la vista
        return view('armonia.operacion.archivos.crear', compact('usuario', 'dictamen_id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Obtener el ID del dictamen de la URL
        $dictamenId = $request->dictamen_id;

        // Si se proporciona un ID, estamos editando un formato existente
        $dictamen = DictamenOp::find($dictamenId);

        // Obtener el ID del usuario autenticado
        $usuarioId = auth()->id();

        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required',
            'archivo' => 'required|file', // Validar que se haya subido un archivo
        ]);

        // Guardar el archivo en la carpeta "armonia"
        $archivoSubido = $request->file('archivo');
        // Guardar el archivo en la carpeta "armonia"
        $nombre = $request->file('nombre');

        // Definir la carpeta principal y la subcarpeta donde se guardarán los PDFs
        $folderPath = "armonia/operacionymantenimiento/{$dictamen->nombre}";
        $subFolderPath = "{$folderPath}/$nombre";

        // Verificar y crear la carpeta principal si no existe
        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        // Verificar y crear la subcarpeta dentro de la carpeta principal si no existe
        if (!Storage::disk('public')->exists($subFolderPath)) {
            Storage::disk('public')->makeDirectory($subFolderPath);
        }

        // Definir la ruta completa del archivo
        $pdfPath = "{$subFolderPath}/{$archivoSubido->getClientOriginalName()}";

        // Guardar el archivo en el sistema de archivos usando put
        Storage::disk('public')->put($pdfPath, file_get_contents($archivoSubido));

        // Obtener la URL pública del archivo
        $pdfUrl = Storage::url($pdfPath);

        // Crear una nueva instancia del modelo Archivos
        $archivo = new ArchivosOp();
        $archivo->nombre = $request->input('nombre'); // Asignar el nombre del archivo
        $archivo->rutadoc = $pdfUrl; // Guardar la URL del archivo en la base de datos
        $archivo->numdicop_id = $dictamenId; // Asignar el dictamen_id obtenido de la URL
        $archivo->save(); // Guardar la nueva entrada en la base de datos

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
        // Obtener el usuario autenticado actualmente
        $usuario = auth()->user();
        // Buscar el plano por su ID
        $archivo = ArchivosOp::findOrFail($id);

        $dictamen = $archivo->numdicop_id;
        // Pasar el usuario y el dictamen_id a la vista
        return view('armonia.operacion.archivos.editar', compact('usuario', 'archivo', 'dictamen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required',
            'archivo' => 'file', // El archivo es opcional en la actualización
        ]);

        // Buscar el plano por su ID
        $archivo = ArchivosOp::findOrFail($id);

        // Actualizar los campos
        $archivo->nombre = $request->nombre;

        // Verificar si se proporcionó un nuevo archivo
        if ($request->hasFile('archivo')) {
            // Obtener la ruta del archivo anterior
            $rutaArchivoAnterior = storage_path('app/public/' . $archivo->rutadoc);

            // Verificar si el archivo anterior existe y eliminarlo
            if (file_exists($rutaArchivoAnterior)) {
                unlink($rutaArchivoAnterior); // Eliminar el archivo anterior del sistema de archivos
            }

            // Guardar el nuevo archivo en el sistema de archivos
            $archivoSubido = $request->file('archivo');
            $rutaArchivo = $archivoSubido->store('public/archivos');
            $archivo->rutadoc = str_replace('public/', '', $rutaArchivo); // Guardar la ruta del archivo en la base de datos
        }

        // Guardar los cambios en la base de datos
        $archivo->save();

        // Redirigir al usuario a la página de lista de planos
        return redirect()->route('archivos.index', ['dictamen_id' => $archivo->numdicop_id])->with('success', 'Archivo actualizado exitosamente');
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
        return redirect()->route('archivos.index', ['dictamen_id' => $archivo->numdicop_id])->with('success', 'Documento eliminado exitosamente');
    }
}
