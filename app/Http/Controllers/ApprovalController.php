<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\DictamenOp;
use Illuminate\Support\Facades\Storage;


class ApprovalController extends Controller
{
    public function index()
    {
        // Obtener todos los dictámenes pendientes de aprobación para eliminar
        $dictamenes = DictamenOp::where('pending_deletion', true)->get();

        return view('notificaciones.index', compact('dictamenes'));
    }

    public function show($id)
    {
        $dictamen = DictamenOp::findOrFail($id);
        return view('notificaciones.show', compact('dictamen'));
    }

    public function approveDeletion(Request $request, $id)
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

        return redirect()->route('notificaciones.index')->with('success', 'Dictamen eliminado exitosamente');
    }
    public function cancelDeletion($id)
    {
        // Aquí podrías implementar la lógica necesaria para cancelar la eliminación del dictamen.
        // Por ejemplo, puedes marcar el dictamen como no pendiente de eliminación.
        $dictamen = DictamenOp::findOrFail($id);

        // Marcar el dictamen como pendiente de eliminación
        $dictamen->pending_deletion = false;
        $dictamen->save();


        return redirect()->route('notificaciones.index')->with('success', 'Eliminación del dictamen cancelada correctamente');
    }
}
