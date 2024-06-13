<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServicioAnexo;
use App\Models\DictamenOp;

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
        $pendingDeletionsDictamen = DictamenOp::where('pending_deletion', 1)->get();

        // Obtener servicios con estado 0 eliminacion
        $pendingDeletionsServicioAn = ServicioAnexo::where('pending_deletion_servicio', 1)->get();

        return view('partials.notifications-servicios', compact('pendingDeletionsServicio', 'pendingDeletionsDictamen', 'pendingDeletionsServicioAn'));
    }
}
