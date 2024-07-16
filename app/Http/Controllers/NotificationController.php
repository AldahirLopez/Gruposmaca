<?php

namespace App\Http\Controllers;

use App\Models\ServicioOperacion;
use Illuminate\Http\Request;
use App\Models\ServicioAnexo;
use App\Models\DictamenOp;
use Illuminate\Support\Facades\Auth;
class NotificationController extends Controller
{
    public function fetchNotifications()
    {
        // Obtener servicios con estado 0 aprobacion
        $pendingDeletionsServicio = ServicioAnexo::where([
            ['pending_apro_servicio', '=', 0],
            ['pending_deletion_servicio', '=', 0]
        ])->get();

        // Obtener dictamenes con estado 0 eliminacion
        $pendingDeletionsDictamen = ServicioOperacion::where('pending_deletion_servicio', 1)->get();

        // Obtener servicios con estado 0 eliminacion
        $pendingDeletionsServicioAn = ServicioAnexo::where('pending_deletion_servicio', 1)->get();

        return view('partials.notifications-servicios', compact('pendingDeletionsServicio', 'pendingDeletionsDictamen', 'pendingDeletionsServicioAn'));
    }


    public function notificacionesAprobacion(){
        
        // Obtener el usuario autenticado
         $usuario = Auth::user();

         // Verificar si el usuario es administrador
         if (auth()->check() && $usuario->hasAnyRole(['Administrador', 'Auditor'])) {
             // Si es administrador, obtener todos los dictámenes
             $servicios = ServicioAnexo::all();
             $operaciones=ServicioOperacion::all();
 
         } else {
             // Si no es administrador, obtener solo los dictámenes del usuario autenticado
             $servicios = ServicioAnexo::where('usuario_id', $usuario->id)->get();
             $operaciones = ServicioOperacion::where('usuario_id', $usuario->id)->get();
         }
 
         // Pasar los dictámenes a la vista
         return view('notificaciones.aprobacion', compact('servicios','operaciones'));


    }
}
