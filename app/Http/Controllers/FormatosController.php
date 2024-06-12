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
    $request->validate([
        'nombre' => 'required|string|max:255',
        'archivo' => 'nullable|file', // El archivo es opcional para la edición
    ]);

    if ($id) {
        // Si se proporciona un ID, estamos editando un formato existente
        $formato = FormatoVigente::findOrFail($id);

        // Guardar el formato actual en el historial
        $formatoHistorico = new HistorialFormato();
        $formatoHistorico->formato_id = $formato->id;
        $formatoHistorico->nombre = $formato->nombre;

        // Mover el archivo del formato vigente a la carpeta de historial
        $rutaAntigua = 'public/' . $formato->rutadoc;
        $nombreArchivoAntiguo = basename($rutaAntigua);
        $timestamp = now()->format('YmdHis');
        $nuevoNombreArchivo = pathinfo($nombreArchivoAntiguo, PATHINFO_FILENAME) . '_' . $timestamp . '.' . pathinfo($nombreArchivoAntiguo, PATHINFO_EXTENSION);
        $nuevaRutaHistorial = 'public/armonia/anexo_30/historialformatos/' . $formato->nombre . '/' . $nuevoNombreArchivo;

        if (Storage::exists($rutaAntigua)) {
            Storage::move($rutaAntigua, $nuevaRutaHistorial);
        }

        // Actualizar la ruta del documento en el historial
        $formatoHistorico->rutadoc = str_replace('public/', '', $nuevaRutaHistorial);
        $formatoHistorico->save();
    } else {
        // Si no se proporciona un ID, estamos creando un nuevo formato
        $formato = new FormatoVigente();
    }

    // Actualizar el nombre
    $formato->nombre = $request->input('nombre');

    // Si se sube un nuevo archivo, reemplazar el existente
    if ($request->hasFile('archivo')) {
        // Guardar el archivo en el sistema de archivos
        $archivoSubido = $request->file('archivo');
        $nombreArchivo = $archivoSubido->getClientOriginalName();
        $rutaArchivo = $archivoSubido->storeAs('public/armonia/anexo_30/formatosvigentes/' . $formato->nombre, $nombreArchivo);

        // Actualizar la ruta del archivo
        $formato->rutadoc = str_replace('public/', '', $rutaArchivo);
    }

    // Guardar los cambios
    $formato->save();

    return redirect()->route('listar.anexo30')->with('success', 'Formato guardado correctamente');
}





    public function ListarAnexo30()
    {

        // Obtener el nombre del dictamen
        $archivos = FormatoVigente::all();

        // Pasar el nombre del dictamen, los archivos y el ID del dictamen a la vista
        return view('armonia.formatos.anexo30.index', ['archivos' => $archivos]);
    }

    public function destroy($id)
    {
        // Intentar encontrar el formato vigente
        $formato = FormatoVigente::findOrFail($id);

        // Eliminar el archivo del sistema de archivos
        Storage::delete('public/armonia/anexo_30/formatosvigentes/' . $formato->rutadoc);

        // Eliminar el registro de la base de datos
        $formato->delete();

        return redirect()->route('listar.anexo30')->with('success', 'Formato eliminado correctamente');
    }

    public function create()
    {
        // No necesitas hacer nada aquí, simplemente mostrar la vista de creación
        return view('armonia.formatos.anexo30.edit');
    }

    public function edit($id)
    {
        // Buscar el formato a editar
        $formato = FormatoVigente::findOrFail($id);

        // Mostrar la vista de edición con los datos del formato
        return view('armonia.formatos.anexo30.edit', ['formato' => $formato]);
    }
}
