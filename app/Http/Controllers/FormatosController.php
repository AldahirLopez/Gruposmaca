<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormatoVigente;
use App\Models\HistorialFormato;
use Illuminate\Support\Facades\Storage;

class FormatosController extends Controller
{
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
            $formatoHistorico->rutadoc = $formato->rutadoc;

            // Mover el archivo del formato vigente a la carpeta de historial
            $rutaAntigua = 'public/armonia/operacionymantenimiento/formatos/' . $formato->rutadoc;
            $nuevaRutaHistorial = 'public/armonia/operacionymantenimiento/historialformatos/' . $formato->rutadoc;
            if (Storage::exists($rutaAntigua)) {
                Storage::move($rutaAntigua, $nuevaRutaHistorial);
            }
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
            $rutaArchivo = $archivoSubido->store('public/armonia/operacionymantenimiento/formatosvigentes/' . $formato->nombre);

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
        Storage::delete('public/armonia/operacionymantenimiento/formatosvigentes/' . $formato->rutadoc);

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
