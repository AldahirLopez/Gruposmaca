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
        $this->middleware('permission:ver-formato|crear-formato|editar-formato|borrar-formato', ['only' => ['index']]);
        $this->middleware('permission:crear-formato', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-formato', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-formato', ['only' => ['destroy']]);
    }

    public function index()
    {

        // Obtener el nombre del dictamen
        $archivos = HistorialFormato::all();

        // Pasar el nombre del dictamen, los archivos y el ID del dictamen a la vista
        return view('armonia.historialformatos.anexo30.index', ['archivos' => $archivos]);
    }

    public function destroy($id)
    {
        // Intentar encontrar el formato vigente
        $formato = HistorialFormato::findOrFail($id);

        // Eliminar el archivo del sistema de archivos
        Storage::delete('public/armonia/operacionymantenimiento/historialformatos/' . $formato->rutadoc);

        // Eliminar el registro de la base de datos
        $formato->delete();

        return redirect()->route('armonia.historialformatos.anexo30.index')->with('success', 'Formato Historico eliminado correctamente');
    }

    public function filtrarArchivos(Request $request)
    {
        $nombre = $request->input('nombre');

        if ($nombre) {
            $archivos = HistorialFormato::where('nombre', $nombre)->orderBy('created_at', 'desc')->get();
        } else {
            $archivos = HistorialFormato::orderBy('created_at', 'desc')->get();
        }

        // Añadir el formato de fecha en el servidor
        $archivos = $archivos->map(function ($archivo) {
            $archivo->formatted_date = $archivo->created_at->format('d-m-Y');
            return $archivo;
        });

        return response()->json($archivos);
    }


}
