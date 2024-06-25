<?php
namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\Auth; // Importa la clase Auth
use App\Http\Controllers\Controller;
use App\Models\ServicioAnexo;
use Illuminate\Http\Request;

class Servicio_Anexo_30Controller extends Controller
{

    protected $connection = 'segunda_db';

    function __construct()
    {
        $this->middleware('permission:ver-anexo|crear-anexo|editar-anexo|borrar-anexo', ['only' => ['index']]);
        $this->middleware('permission:crear-anexo', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-anexo', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-anexo', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        //Vista inicial de anexo 30 divida en tarjetas
        return view('armonia.servicio_anexo_30.index');
    }

    public function apro_servicio_anexo()
    {
        // Obtener el usuario autenticado
        $usuario = Auth::user();

        // Verificar si el usuario es administrador
        if (auth()->check() && $usuario->hasAnyRole(['Administrador', 'Auditor'])) {
            // Si es administrador, obtener todos los dictámenes
            $servicios = ServicioAnexo::all();

        } else {
            // Si no es administrador, obtener solo los dictámenes del usuario autenticado
            $servicios = ServicioAnexo::where('usuario_id', $usuario->id)->get();
        }

        // Pasar los dictámenes a la vista
        return view('armonia.servicio_anexo_30.aprobacion_servicio.index', compact('servicios'));
    }

    public function apro($id)
    {
        try {
            // Buscar el servicio por su ID
            $servicio = ServicioAnexo::findOrFail($id);

            // Establecer pending_apro_servicio como true
            $servicio->pending_apro_servicio = true;
            $servicio->save();

            // Redireccionar con un mensaje de éxito
            return redirect()->route('apro.anexo')->with('success', 'Servicio aprobado correctamente.');
        } catch (ModelNotFoundException $e) {
            // Manejar la excepción si el servicio no se encuentra
            return redirect()->route('apro.anexo') > with('error', 'Servicio no encontrado.');
        }
    }
}
