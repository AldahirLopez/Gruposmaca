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
        $this->middleware('permission:ver-formato_historial|crear-formato_historial|editar-formato_historial|borrar-formato_historial', ['only' => ['index']]);
        $this->middleware('permission:crear-formato_historial', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-formato_historial', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-formato_historial', ['only' => ['destroy']]);
    }

    public function index($tipo_doc = null)
    {
        // Define a mapping for tipo_doc values
        $tipoDocNames = [
            'anexo30' => 'Anexo 30',
            'operacion' => 'Operación y Mantenimiento',
            'diseno' => 'Diseño',
            'construccion' => 'Construcción'
        ];

        // Get the human-readable name for the tipo_doc
        $tipoDocName = $tipo_doc ? ($tipoDocNames[$tipo_doc] ?? 'Formatos') : 'Formatos';

        // Filter the files by tipo_doc if provided, otherwise get all
        $archivos = $tipo_doc
            ? HistorialFormato::where('tipo_doc', $tipo_doc)->get()
            : HistorialFormato::all();

        // Pass the files and the tipo_doc name to the view
        return view('armonia.historialformatos.index', [
            'archivos' => $archivos,
            'tipo_doc_name' => $tipoDocName,
            'tipo_doc' => $tipo_doc
        ]);
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

        // Obtener el tipo de documento para redirigir
        $tipoDoc = request()->query('tipo_doc', 'anexo30'); // Valor por defecto 'anexo30'

        // Redirigir con un mensaje de éxito
        switch ($tipoDoc) {
            case 'anexo30':
                return redirect()->route('historialformatos.index', ['tipo_doc' => 'anexo30'])->with('success', 'Formato eliminado correctamente');
            case 'operacion':
                return redirect()->route('historialformatos.index', ['tipo_doc' => 'operacion'])->with('success', 'Formato eliminado correctamente');
            case 'diseno':
                return redirect()->route('historialformatos.index', ['tipo_doc' => 'diseno'])->with('success', 'Formato eliminado correctamente');
            case 'construccion':
                return redirect()->route('historialformatos.index', ['tipo_doc' => 'construccion'])->with('success', 'Formato eliminado correctamente');
            default:
                return redirect()->route('historialformatos.index', ['tipo_doc' => 'anexo30'])->with('success', 'Formato eliminado correctamente');
        }
    }


    public function filtrarArchivos(Request $request)
    {
        $tipo_doc = $request->input('tipo_doc');
        $nombre = $request->input('nombre');

        $query = HistorialFormato::query();

        // Filter by tipo_doc if provided
        if ($tipo_doc) {
            $query->where('tipo_doc', $tipo_doc);
        }

        // Filter by nombre if provided
        if ($nombre) {
            $query->where('nombre', $nombre);
        }

        // Order by created_at descending
        $archivos = $query->orderBy('created_at', 'desc')->get();

        // Add formatted date to each archivo
        $archivos = $archivos->map(function ($archivo) {
            $archivo->formatted_date = $archivo->created_at->format('d-m-Y');
            return $archivo;
        });

        return response()->json($archivos);
    }



}
