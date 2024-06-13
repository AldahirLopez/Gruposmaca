<?php

namespace App\Http\Controllers;

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

    public function approveDeletion(Request $request, $id)
    {

        try {
            // Intenta encontrar el dictamen en la primera tabla
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

            return redirect()->route('notificaciones.index')->with('success', 'Dictamen eliminado exitosamente');
        } catch (ModelNotFoundException $e) {
            // Si no se encuentra en la primera tabla, busca en la segunda tabla
            $servicio = ServicioAnexo::where('nomenclatura', $id)->firstOrFail();

            // Eliminar el dictamen
            $servicio->delete();

            return redirect()->route('notificaciones.index')->with('success', 'Dictamen eliminado exitosamente');
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
            $servicio->pending_deletion = false;
            $servicio->save();
        }

        return redirect()->route('notificaciones.index')->with('success', 'Eliminación del dictamen cancelada correctamente');
    }
}
