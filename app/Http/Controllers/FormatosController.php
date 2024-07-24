<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormatoVigente;
use App\Models\HistorialFormato;
use Illuminate\Support\Facades\Storage;

class FormatosController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:ver-formato|crear-formato|editar-formato|borrar-formato', ['only' => ['index']]);
        $this->middleware('permission:crear-formato', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-formato', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-formato', ['only' => ['destroy']]);
    }

    public function save(Request $request, $id = null)
    {
        // Validar los datos del formulario
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'archivo' => 'nullable|file', // El archivo es opcional para la edición
            'tipo_doc' => 'required|string', // Añadir validación para el campo tipo_doc
        ]);

        if ($id) {
            // Si se proporciona un ID, estamos editando un formato existente
            $formato = FormatoVigente::findOrFail($id);

            // Guardar el formato actual en el historial (opcional)
            $this->guardarHistorialFormato($formato);
        } else {
            // Si no se proporciona un ID, estamos creando un nuevo formato
            $formato = new FormatoVigente();
        }

        // Actualizar el nombre y el tipo_doc
        $formato->nombre = $data['nombre'];
        $formato->tipo_doc = $data['tipo_doc'];

        // Si se sube un nuevo archivo, reemplazar el existente
        if ($request->hasFile('archivo')) {
            // Obtener el archivo subido
            $archivoSubido = $request->file('archivo');
            $nombreArchivo = $archivoSubido->getClientOriginalName();

            // Definir la carpeta de destino dentro de 'public/storage'
            $customFolderPath = "armonia/{$data['tipo_doc']}/formatosvigentes/{$formato->nombre}";
            \Log::info("Ruta del directorio: " . $customFolderPath);

            // Verificar si la carpeta principal existe, si no, crearla
            if (!Storage::disk('public')->exists($customFolderPath)) {
                Storage::disk('public')->makeDirectory($customFolderPath);
            }

            // Guardar el archivo en el sistema de archivos
            $rutaArchivo = $archivoSubido->storeAs(
                "public/{$customFolderPath}",
                $nombreArchivo
            );

            // Actualizar la ruta del archivo en la base de datos
            $formato->rutadoc = str_replace('public/', '', $rutaArchivo);
        }

        // Guardar los cambios en el formato
        $formato->save();

        // Redirigir a la vista correspondiente según el tipo_doc
        switch ($data['tipo_doc']) {
            case 'anexo30':
                return redirect()->route('listar.anexo30')->with('success', 'Formato guardado correctamente');
            case 'operacion':
                return redirect()->route('listar.ope')->with('success', 'Formato guardado correctamente');
            case 'diseño':
                return redirect()->route('listar.diseno')->with('success', 'Formato guardado correctamente');
            case 'construccion':
                return redirect()->route('listar.const')->with('success', 'Formato guardado correctamente');
            default:
                return redirect()->route('listar.anexo30')->with('success', 'Formato guardado correctamente');
        }
    }


    private function guardarHistorialFormato($formato)
    {
        $formatoHistorico = new HistorialFormato();
        $formatoHistorico->formato_id = $formato->id;
        $formatoHistorico->nombre = $formato->nombre;
        $formatoHistorico->tipo_doc = $formato->tipo_doc; // Agregar tipo_doc al historial

        // Mover el archivo del formato vigente a la carpeta de historial
        $rutaAntigua = 'public/' . $formato->rutadoc;
        $nombreArchivoAntiguo = basename($rutaAntigua);
        $timestamp = now()->format('YmdHis');
        $nuevoNombreArchivo = pathinfo($nombreArchivoAntiguo, PATHINFO_FILENAME) . '_' . $timestamp . '.' . pathinfo($nombreArchivoAntiguo, PATHINFO_EXTENSION);
        $nuevaRutaHistorial = "public/armonia/{$formato->tipo_doc}/historialformatos/{$formato->nombre}/{$nuevoNombreArchivo}";

        if (Storage::exists($rutaAntigua)) {
            Storage::move($rutaAntigua, $nuevaRutaHistorial);
        }

        // Actualizar la ruta del documento en el historial
        $formatoHistorico->rutadoc = str_replace('public/', '', $nuevaRutaHistorial);
        $formatoHistorico->save();
    }

    public function ListarAnexo30()
    {
        // Filtrar por un valor específico en el campo anexo_30
        $archivos = FormatoVigente::where('tipo_doc', 'anexo30')->get();

        $tipo_doc = 'Anexo 30';

        // Pasar los archivos a la vista
        return view('armonia.formatos.index', ['archivos' => $archivos,'tipo_doc' => $tipo_doc]);
    }

    public function ListarOpe()
    {

        // Filtrar por un valor específico en el campo anexo_30
        $archivos = FormatoVigente::where('tipo_doc', 'operacion')->get();

        $tipo_doc = 'Operacion y Mantenimiento';

        // Pasar los archivos a la vista
        return view('armonia.formatos.index', ['archivos' => $archivos,'tipo_doc' => $tipo_doc]);
    }

    public function ListarDiseno()
    {

        // Filtrar por un valor específico en el campo anexo_30
        $archivos = FormatoVigente::where('tipo_doc', 'diseño')->get();

        $tipo_doc = 'Diseño';

        // Pasar los archivos a la vista
        return view('armonia.formatos.index', ['archivos' => $archivos,'tipo_doc' => $tipo_doc]);
    }

    public function ListarConst()
    {

        // Filtrar por un valor específico en el campo anexo_30
        $archivos = FormatoVigente::where('tipo_doc', 'construccion')->get();

        $tipo_doc = 'Construccion';

        // Pasar los archivos a la vista
        return view('armonia.formatos.index', ['archivos' => $archivos,'tipo_doc' => $tipo_doc]);
    }

    public function destroy($id)
    {
        // Buscar el formato vigente por su ID
        $formato = FormatoVigente::findOrFail($id);

        // Obtener la ruta del archivo almacenada en la base de datos
        $rutaDoc = $formato->rutadoc;

        // Obtener el tipo de documento para la redirección
        $tipoDoc = $formato->tipo_doc;

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

        // Redirigir a la vista correspondiente según el tipo_doc
        switch ($tipoDoc) {
            case 'anexo30':
                return redirect()->route('listar.anexo30')->with('success', 'Formato eliminado correctamente');
            case 'operacion':
                return redirect()->route('listar.ope')->with('success', 'Formato eliminado correctamente');
            case 'diseño':
                return redirect()->route('listar.diseno')->with('success', 'Formato eliminado correctamente');
            case 'construccion':
                return redirect()->route('listar.const')->with('success', 'Formato eliminado correctamente');
            default:
                return redirect()->route('listar.anexo30')->with('success', 'Formato eliminado correctamente');
        }
    }



    public function create()
    {
        // No necesitas hacer nada aquí, simplemente mostrar la vista de creación
        return view('armonia.formatos.edit');
    }

    public function edit($id)
    {
        // Buscar el formato a editar
        $formato = FormatoVigente::findOrFail($id);

        // Mostrar la vista de edición con los datos del formato
        return view('armonia.formatos.edit', ['formato' => $formato]);
    }
}
