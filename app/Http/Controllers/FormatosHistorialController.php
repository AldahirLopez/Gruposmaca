<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormatoVigente;
use App\Models\HistorialFormato;
use Illuminate\Support\Facades\Storage;

class FormatosHistorialController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:ver-formato|crear-formato|editar-formato|borrar-formato', ['only' => ['index']]);
        $this->middleware('permission:crear-formato', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-formato', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-formato', ['only' => ['destroy']]);
    }

    public function index()
    {

        // Obtener el nombre del dictamen
        $archivos = HistorialFormato::all();

        // Pasar el nombre del dictamen, los archivos y el ID del dictamen a la vista
        return view('armonia.historialformatos.anexo30.index', ['archivos' => $archivos]);
    }

    public function destroy($id)
    {
        // Intentar encontrar el formato histórico por su ID
        $formato = HistorialFormato::findOrFail($id);

        // Obtener la ruta del archivo almacenada en el campo rutadoc
        $rutaDoc = $formato->rutadoc;

        // Verificar si el archivo existe en el sistema de almacenamiento
        if (Storage::disk('public')->exists($rutaDoc)) {
            // Eliminar el archivo del sistema de almacenamiento
            Storage::disk('public')->delete($rutaDoc);
        } else {
            // Registrar un mensaje de advertencia si no se encuentra el archivo
            \Log::warning('Archivo no encontrado para eliminar: ' . $rutaDoc);
        }

        // Obtener el directorio donde se guarda el archivo
        $carpeta = pathinfo($rutaDoc, PATHINFO_DIRNAME);

        // Verificar si la carpeta está vacía
        if (Storage::disk('public')->allFiles($carpeta) === []) {
            // Si la carpeta está vacía, eliminarla
            Storage::disk('public')->deleteDirectory($carpeta);
        }

        // Eliminar el registro de la base de datos
        $formato->delete();

        // Redirigir con un mensaje de éxito
        return redirect()->route('historialformatos.anexo30.index')->with('success', 'Formato Histórico eliminado correctamente');
    }

    public function filtrarArchivos(Request $request)
    {
        $nombre = $request->input('nombre');

        if ($nombre) {
            $archivos = HistorialFormato::where('nombre', $nombre)->orderBy('created_at', 'desc')->get();
        } else {
            $archivos = HistorialFormato::orderBy('created_at', 'desc')->get();
        }

        // Añadir el formato de fecha en el servidor
        $archivos = $archivos->map(function ($archivo) {
            $archivo->formatted_date = $archivo->created_at->format('d-m-Y');
            return $archivo;
        });

        return response()->json($archivos);
    }


}
