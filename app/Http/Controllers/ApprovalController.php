<?php

namespace App\Http\Controllers;

use App\Models\Datos_Servicio;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\DictamenOp;
use App\Models\ServicioAnexo;
use Illuminate\Support\Facades\Storage;

use Illuminate\Database\Eloquent\ModelNotFoundException;


class ApprovalController extends Controller
{
    public function index()
    {
        // Obtener todos los dictámenes pendientes de aprobación para eliminar
        $dictamenes = DictamenOp::where('pending_deletion', true)->get();
        $servicios = ServicioAnexo::where('pending_deletion_servicio', true)->get();

        return view('notificaciones.index', compact('dictamenes', 'servicios'));
    }

    public function show($id)
    {
        try {
            // Intenta encontrar el dictamen en la primera tabla
            $variable = DictamenOp::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            // Si no se encuentra en la primera tabla, busca en la segunda tabla
            $variable = ServicioAnexo::where('nomenclatura', $id)->firstOrFail();
        }

        // Ahora puedes pasar el dictamen encontrado a la vista
        return view('notificaciones.show', compact('variable'));
    }

    public function approveDictamenDeletion(Request $request, $id)
    {
        try {
            // Intenta encontrar el dictamen en la primera tabla
            $dictamen = DictamenOp::findOrFail($id);

            // Obtener los archivos relacionados
            $archivos = $dictamen->dicarchivos;

            // Eliminar los archivos del sistema de archivos
            foreach ($archivos as $archivo) {
                // Obtener la ruta del archivo y verificar si existe antes de eliminarlo
                $rutaDoc = $archivo->rutadoc;
                if (Storage::exists("public/{$rutaDoc}")) {
                    Storage::delete("public/{$rutaDoc}");
                } else {
                    // Registrar un mensaje de advertencia si el archivo no se encuentra
                    \Log::warning('Archivo no encontrado para eliminar: ' . $rutaDoc);
                }
            }

            // Obtener la nomenclatura para la carpeta de archivos
            $nombre = $dictamen->nombre;
            $customFolderPath = "NOM-005/{$nombre}";

            // Eliminar la carpeta de archivos si existe
            if (Storage::disk('public')->exists($customFolderPath)) {
                Storage::disk('public')->deleteDirectory($customFolderPath);
            }

            // Eliminar los registros relacionados en dicarchivos
            $dictamen->dicarchivos()->delete();

            // Eliminar el dictamen
            $dictamen->delete();

            return redirect()->route('notificaciones.index')->with('success', 'Dictamen eliminado exitosamente');
        } catch (ModelNotFoundException $e) {
            // Manejar la excepción si no se encuentra en la primera tabla
            return redirect()->back()->with('error', 'Dictamen no encontrado en la primera tabla.');
        }
    }


    public function approveServicioDeletion(Request $request, $id)
    {
        try {
            // Intenta encontrar el servicio en la segunda tabla
            $servicio = ServicioAnexo::where('nomenclatura', $id)->firstOrFail();

            // Eliminar primero la referencia en la tabla datos_servicio_anexo_30 si existe
            Datos_Servicio::where('servicio_anexo_id', $servicio->id)->delete();

            // Obtener la nomenclatura para la carpeta de archivos
            $nomenclatura = $servicio->nomenclatura;
            $customFolderPath = "servicios_anexo30/{$nomenclatura}";

            // Eliminar la carpeta de archivos si existe
            if (Storage::disk('public')->exists($customFolderPath)) {
                Storage::disk('public')->deleteDirectory($customFolderPath);
            }

            // Eliminar el servicio
            $servicio->delete();

            return redirect()->route('notificaciones.index')->with('success', 'Servicio eliminado exitosamente');
        } catch (ModelNotFoundException $e) {
            // Manejar la excepción si no se encuentra el servicio
            return redirect()->back()->with('error', 'Servicio no encontrado.');
        }
    }

    public function cancelDeletion($id)
    {

        try {
            // Intenta encontrar el dictamen en la primera tabla
            $dictamen = DictamenOp::findOrFail($id);
            // Marcar el dictamen como pendiente de eliminación
            $dictamen->pending_deletion = false;
            $dictamen->save();
        } catch (ModelNotFoundException $e) {
            // Si no se encuentra en la primera tabla, busca en la segunda tabla
            $servicio = ServicioAnexo::where('nomenclatura', $id)->firstOrFail();
            // Marcar el dictamen como pendiente de eliminación
            $servicio->pending_deletion_servicio = false;
            $servicio->save();
        }

        return redirect()->route('notificaciones.index')->with('success', 'Eliminación del dictamen cancelada correctamente');
    }


}
