<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion_Servicio_Anexo30;
use App\Models\Cotizacion_Operacion;
use App\Models\Expediente_Operacion;
use App\Models\Expediente_Servicio_Anexo_30;
use App\Models\Documento_Servicio_operacion;
use App\Models\Documento_Servicio_Anexo;
use App\Models\Factura_Anexo;
use App\Models\Pago_Anexo;
use App\Models\Pago_Operacion;
use App\Models\Acta_Operacion;
use App\Models\Datos_Servicio;
use App\Models\ServicioOperacion;
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
        $dictamenes = ServicioOperacion::where('pending_deletion_servicio', true)->get();
        $servicios = ServicioAnexo::where('pending_deletion_servicio', true)->get();

        return view('notificaciones.index', compact('dictamenes', 'servicios'));
    }

    /*public function show($id)
    {
        $tipo_servicio;
        try {
            // Intenta encontrar el dictamen en la primera tabla
            $variable = ServicioOperacion::findOrFail($id);
            $tipo_servicio="Operacion";
        } catch (ModelNotFoundException $e) {
            // Si no se encuentra en la primera tabla, busca en la segunda tabla
            $variable = ServicioAnexo::where('nomenclatura', $id)->firstOrFail();
            $tipo_servicio="Anexo";
        }

        // Ahora puedes pasar el dictamen encontrado a la vista
        return view('notificaciones.show', compact('variable','tipo_servicio'));
    }*/

    public function approveDictamenDeletion(Request $request, $id)
    {
        try {
            // Intenta encontrar el dictamen en la primera tabla
            $dictamen = ServicioOperacion::findOrFail($id);

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
            $customFolderPath = "OperacionyMantenimiento/{$nombre}";

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

            // Eliminar registros relacionados en cotizacion_anexo_30
            Cotizacion_Servicio_Anexo30::where('servicio_anexo_id', $servicio->id)->delete();

             // Eliminar registros relacionados en Expediente operacion
            Expediente_Servicio_Anexo_30::where('servicio_anexo_id', $servicio->id)->delete();
            
            Documento_Servicio_Anexo::where('servicio_id', $servicio->id)->delete();
        
             //Elimnar regustros relacionados en pago
             $pago=Pago_Anexo::where('servicio_anexo_id', $servicio->id)->first();
             
             if($pago){
                Factura_Anexo::where('id_pago', $pago->id)->delete();
                $pago->delete();

             }

           
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

    public function approveServicioOperacionDeletion(Request $request, $id)
    {
       
        try {
            // Intenta encontrar el servicio en la segunda tabla
            $servicio = ServicioOperacion::where('nomenclatura', $id)->firstOrFail();

            // Eliminar registros relacionados en Cotizacion operacion
            Cotizacion_Operacion::where('servicio_id', $servicio->id)->delete();

            // Eliminar registros relacionados en Expediente operacion
            Expediente_Operacion::where('operacion_mantenimiento_id', $servicio->id)->delete();

            //Eliminar registros relacionados en documentacion
            Documento_Servicio_operacion::where('servicio_id', $servicio->id)->delete();

            //Elimnar regustros relacionados en acta de verificacion 
            Acta_Operacion::where('servicio_id', $servicio->id)->delete();

            //Elimnar regustros relacionados en Pago operaciob
            Pago_Operacion::where('servicio_id', $servicio->id)->delete();

    
            // Obtener la nomenclatura para la carpeta de archivos
            $nomenclatura = $servicio->nomenclatura;
            $customFolderPath = "OperacionyMantenimiento/{$nomenclatura}";

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
            $dictamen = ServicioOperacion::findOrFail($id);
            // Marcar el dictamen como pendiente de eliminación
            $dictamen->pending_deletion_servicio = false;
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
