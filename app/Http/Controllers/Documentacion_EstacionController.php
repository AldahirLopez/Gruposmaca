<?php

namespace App\Http\Controllers;

use App\Models\Documento_Estacion;
use App\Models\Estacion;
use App\Http\Controllers\Controller;
use App\Models\Usuario_Estacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;



use Illuminate\Support\Facades\Auth; // Importa la clase Auth

class Documentacion_EstacionController extends Controller
{

    protected $connection = 'segunda_db';
    public function index(Request $request)
    {
        try {
            if ($request->has('id')) {
                $id = $request->input('id');
                // Obtener los documentos de la estación específica
                $documentos = [];

                // Obtener la ruta de la carpeta de documentos para esta estación
                $estacion = Estacion::findOrFail($id);
                $razonSocial = str_replace([' ', '.'], '_', $estacion->razon_social);
                $customFolderPath = "armonia/estaciones/{$razonSocial}/documentacion";

                // Verificar si la carpeta existe en el almacenamiento
                if (Storage::disk('public')->exists($customFolderPath)) {
                    // Listar los archivos en la carpeta
                    $archivos = Storage::disk('public')->files($customFolderPath);

                    // Construir la colección de documentos con nombre y ruta
                    foreach ($archivos as $archivo) {
                        $nombreArchivo = pathinfo($archivo, PATHINFO_FILENAME);
                        $rutaArchivo = Storage::url($archivo); // Obtener la URL del archivo
                        $documentos[] = (object) [
                            'nombre' => $nombreArchivo,
                            'ruta' => $rutaArchivo
                        ];
                    }
                }

                // Pasar los datos a la vista
                return view('armonia.documentos_estacion.index', compact('documentos', 'id','estacion'));
            } else {
                return redirect()->route('documentacion_estacion.index')->with('error', 'No se proporcionó un ID de estación.');
            }
        } catch (\Exception $e) {
            return redirect()->route('documentacion_estacion.index')->with('error', 'Error al obtener la documentación: ' . $e->getMessage());
        }
    }


    public function store(Request $request)
    {
        // Validar los datos del formulario
        $data = $request->validate([
            'nombre' => 'required',
            'rutadoc_estacion' => 'required|file',
            'estacion_id' => 'required',
            'razon_social' => 'required',
        ]);

        try {
            // Buscar un documento existente por su ID
            $documento = Documento_Estacion::firstOrNew(['estacion_id' => $data['estacion_id']]);

            // Si no existe un documento con ese ID, lanzará una excepción NotFoundHttpException

            // Si se sube un archivo, guardarlo
            if ($request->hasFile('rutadoc_estacion')) {
                // Obtener el archivo subido y el nombre especificado por el usuario
                $archivoSubido = $request->file('rutadoc_estacion');
                $nombreArchivoPersonalizado = $data['nombre'] . '.' . $archivoSubido->getClientOriginalExtension(); // Nombre personalizado con extensión original

                // Definir la carpeta de destino dentro de 'public/storage'
                $razonSocial = str_replace([' ', '.'], '_', $data['razon_social']); // Eliminar espacios y puntos en la razón social
                $customFolderPath = "armonia/estaciones/{$razonSocial}/documentacion"; // Añadir subdirectorio 'documentacion'

                // Verificar si la carpeta principal existe, si no, crearla
                if (!Storage::disk('public')->exists($customFolderPath)) {
                    Storage::disk('public')->makeDirectory($customFolderPath);
                }

                // Guardar el archivo en el sistema de archivos con el nombre personalizado
                $rutaArchivo = $archivoSubido->storeAs(
                    "public/{$customFolderPath}",
                    $nombreArchivoPersonalizado
                );

                // Actualizar la ruta del archivo en la base de datos
                $documento->rutadoc_estacion = str_replace('public/', '', $customFolderPath);
            }

            // Asignar los demás datos al modelo Documento_Estacion
            $documento->estacion_id = $data['estacion_id'];
            $documento->usuario_id = Auth::id(); // Obtener el usuario autenticado y asignar su ID al modelo
            $documento->save();

            // Redirigir con un mensaje de éxito
            return redirect()->route('documentacion_estacion.index', ['id' => $data['estacion_id']])->with('success', 'Documento guardado exitosamente.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Capturar y manejar la excepción si no se encuentra el documento
            return redirect()->route('documentacion_estacion.index', ['id' => $data['estacion_id']])->with('error', 'Documento no guardado exitosamente.');
        }
    }
}
