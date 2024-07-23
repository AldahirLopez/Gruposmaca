<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Dictamen_Diseño;
use App\Models\Estacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\Auth;

class DictamenDiseñoController extends Controller
{
    protected $connection = 'segunda_db';
    public function index()
    {
        $dictamenes = Dictamen_Diseño::all();
        $estaciones = Estacion::all();
        $usuariosOperacionMantenimiento = User::whereHas('roles', function ($query) {
            $query->where('name', 'Operacion y Mantenimiento');
        })->get();

        $todosLosUsuarios = User::all();

        return view("armonia.diseño.index", compact('estaciones', 'usuariosOperacionMantenimiento', 'todosLosUsuarios', 'dictamenes'));
    }

    public function store(Request $request)
    {
        try {
            // Verificar todos los datos de la solicitud
            $data = $request->validate([
                'fecha_emision' => 'required|date',
                'fecha_inicio' => 'required|date',
                'estacion_id' => 'required',
                'usuario_operacion_mantenimiento' => 'required|exists:users,id',
                'todos_los_usuarios' => 'required|exists:users,id',
            ]);

            // Convertir las fechas al formato deseado
            $fecha_emision = Carbon::createFromFormat('Y-m-d', $data['fecha_emision'])->format('d-m-Y');
            $fecha_inicio = Carbon::createFromFormat('Y-m-d', $data['fecha_inicio'])->format('d-m-Y');

            // Obtener el usuario seleccionado
            $usuario = User::findOrFail($data['usuario_operacion_mantenimiento']);
            $gerente = User::findOrFail($data['todos_los_usuarios']);

            // Generar la nomenclatura basada en el usuario seleccionado
            $nomenclatura = $this->generarNomenclatura($usuario);

            // Obtener datos de la estación desde la segunda base de datos
            $estacion = Estacion::on('segunda_db')->findOrFail($data['estacion_id']);

            // Cargar las plantillas de Word
            $templatePaths = [
                'DICTAMEN DISEÑO.docx',
            ];

            // Definir la carpeta de destino
            $customFolderPath = "Dictames/{$nomenclatura}";
            $subFolderPath = "{$customFolderPath}/Diseño";

            // Crear la carpeta personalizada si no existe
            if (!Storage::disk('public')->exists($customFolderPath)) {
                Storage::disk('public')->makeDirectory($customFolderPath);
            }

            // Verificar y crear la subcarpeta si no existe
            if (!Storage::disk('public')->exists($subFolderPath)) {
                Storage::disk('public')->makeDirectory($subFolderPath);
            }

            // Reemplazar marcadores en todas las plantillas
            foreach ($templatePaths as $templatePath) {
                $templateProcessor = new TemplateProcessor(storage_path("app/templates/Diseño/{$templatePath}"));

                // Reemplazar todos los marcadores con los datos del formulario
                $templateProcessor->setValue('nomenclatura', $nomenclatura);
                $templateProcessor->setValue('fecha_inicio', $fecha_inicio);
                $templateProcessor->setValue('fecha_emision', $fecha_emision);
                $templateProcessor->setValue('razon_social', $estacion->razon_social);
                $templateProcessor->setValue('direccion_fiscal', $estacion->domicilio_fiscal);
                $templateProcessor->setValue('numero', $estacion->telefono);
                $templateProcessor->setValue('correo', $estacion->correo_electronico);
                $templateProcessor->setValue('direccion_estacion', $estacion->domicilio_estacion_servicio);
                $templateProcessor->setValue('verificador', $usuario->name);
                $templateProcessor->setValue('gerente', $gerente->name);
                $templateProcessor->setValue('representante', $estacion->nombre_representante_legal);

                // Crear un nombre de archivo basado en la nomenclatura
                $fileName = pathinfo($templatePath, PATHINFO_FILENAME) . "_{$nomenclatura}.docx";

                // Guardar la plantilla procesada en la carpeta de destino
                $templateProcessor->saveAs(storage_path("app/public/{$subFolderPath}/{$fileName}"));
            }

            // Guardar el dictamen de diseño en la base de datos
            $dictamen = new Dictamen_Diseño;
            $dictamen->nomenclatura = $nomenclatura;
            $dictamen->fecha_inicio = $fecha_inicio;
            $dictamen->fecha_emision = $fecha_emision;
            $dictamen->rutadoc_diseño = $subFolderPath;
            $dictamen->estacion_id = $data['estacion_id'];
            $usuario = Auth::user();
            $dictamen->usuario_id = $usuario->id;
            $dictamen->save();

            // Crear la lista de archivos generados con sus URLs
            $generatedFiles = array_map(function ($templatePath) use ($subFolderPath, $nomenclatura) {
                $fileName = pathinfo($templatePath, PATHINFO_FILENAME) . "_{$nomenclatura}.docx";
                return [
                    'name' => $fileName,
                    'url' => Storage::url("{$subFolderPath}/{$fileName}"),
                ];
            }, $templatePaths);

            // Redireccionar a la vista con los archivos generados
            return redirect()->route('diseño.index')
                ->with('generatedFiles', $generatedFiles);
        } catch (\Exception $e) {
            // Capturar y registrar cualquier excepción ocurrida
            \Log::error("Error al generar documentos: " . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al procesar la solicitud. Por favor, intenta de nuevo más tarde.'], 500);
        }
    }

    public function generarNomenclatura($usuario)
    {
        $iniciales = $this->obtenerIniciales($usuario);
        $anio = date('Y');
        $nomenclatura = '';
        $numero = 1;

        do {
            $nomenclatura = "D-$iniciales-$numero-$anio";
            $existe = Dictamen_Diseño::where('nomenclatura', $nomenclatura)->exists();

            if ($existe) {
                $numero++;
            } else {
                break;
            }
        } while (true);

        return $nomenclatura;
    }

    private function obtenerIniciales($usuario)
    {
        $nombres = explode(' ', $usuario->name); // Suponiendo que el campo de nombres es 'name'
        $iniciales = '';
        $contador = 0;

        foreach ($nombres as $nombre) {
            if ($contador < 3) {
                $iniciales .= substr($nombre, 0, 1);
                $contador++;
            } else {
                break;
            }
        }

        return strtoupper($iniciales);
    }

    public function download($id)
    {
        // Obtener el dictamen de diseño por ID
        $dictamen = Dictamen_Diseño::findOrFail($id);

        // Construir la ruta completa del archivo
        $filePath = storage_path("app/public/{$dictamen->rutadoc_diseño}/DICTAMEN DISEÑO_{$dictamen->nomenclatura}.docx");

        // Verificar si el archivo existe
        if (file_exists($filePath)) {
            // Descargar el archivo
            return response()->download($filePath);
        }

        // En caso de que el archivo no exista, redireccionar o manejar el error
        abort(404, 'Archivo no encontrado');
    }

    public function subirSustento(Request $request, $id)
    {
        $request->validate([
            'sustento' => 'required|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        $dictamen = Dictamen_Diseño::findOrFail($id);

        // Verificar si ya existe un sustento
        if ($dictamen->rutadoc_sustento_diseño) {
            // Redireccionar o manejar el caso de que ya exista el sustento
            return redirect()->back()->with('error', 'Ya se ha subido un sustento para este dictamen.');
        }

        // Definir la carpeta de destino
        $customFolderPath = "Dictames/{$dictamen->nomenclatura}";
        $subFolderPath = "{$customFolderPath}/Diseño";

        // Crear la carpeta personalizada si no existe
        if (!Storage::disk('public')->exists($customFolderPath)) {
            Storage::disk('public')->makeDirectory($customFolderPath);
        }

        // Verificar y crear la subcarpeta si no existe
        if (!Storage::disk('public')->exists($subFolderPath)) {
            Storage::disk('public')->makeDirectory($subFolderPath);
        }

        // Subir el archivo del sustento con el nombre específico
        $archivoSustento = $request->file('sustento'); // Definir la variable
        $nombreArchivo = "Sustento_{$dictamen->nomenclatura}.pdf";
        $rutaArchivo = $archivoSustento->storeAs($subFolderPath, $nombreArchivo, 'public');

        // Guardar la ruta del sustento en la base de datos
        $dictamen->rutadoc_sustento_diseño = $rutaArchivo;
        $dictamen->save();

        // Redireccionar con un mensaje de éxito
        return redirect()->back()->with('success', 'Sustento subido correctamente.');
    }

    public function destroy($id)
    {
        try {
            // Obtener el dictamen de diseño por ID
            $dictamen = Dictamen_Diseño::findOrFail($id);

            // Construir la ruta completa de la carpeta donde están los archivos
            $folderPath = "Dictames/{$dictamen->nomenclatura}/Diseño";

            // Eliminar los archivos en la carpeta
            if (Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->deleteDirectory($folderPath);
            }

            // Eliminar el registro del dictamen de la base de datos
            $dictamen->delete();

            // Redireccionar con un mensaje de éxito
            return redirect()->route('diseño.index')->with('success', 'Dictamen de diseño eliminado correctamente.');
        } catch (\Exception $e) {
            // Capturar y registrar cualquier excepción ocurrida
            \Log::error("Error al eliminar el dictamen de diseño: " . $e->getMessage());
            return redirect()->route('diseño.index')->with('error', 'Ocurrió un error al eliminar el dictamen de diseño. Por favor, intenta de nuevo más tarde.');
        }
    }
}
