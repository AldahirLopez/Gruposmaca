<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Estacion;
use App\Models\Estacion_Servicio;
use App\Models\Expediente_Servicio_Anexo_30;
use App\Models\ServicioAnexo;
use App\Models\Usuario_Estacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\DB;

class Datos_Servicio_Inspector_Anexo_30Controller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function ExpedienteInspectorAnexo30($slug)
    {
        // Lista de estados de México
        $estados = [
            'Aguascalientes',
            'Baja California',
            'Baja California Sur',
            'Campeche',
            'Chiapas',
            'Chihuahua',
            'Coahuila',
            'Colima',
            'Ciudad de México',
            'Durango',
            'Guanajuato',
            'Guerrero',
            'Hidalgo',
            'Jalisco',
            'México',
            'Michoacán',
            'Morelos',
            'Nayarit',
            'Nuevo León',
            'Oaxaca',
            'Puebla',
            'Querétaro',
            'Quintana Roo',
            'San Luis Potosí',
            'Sinaloa',
            'Sonora',
            'Tabasco',
            'Tamaulipas',
            'Tlaxcala',
            'Veracruz',
            'Yucatán',
            'Zacatecas'
        ];

        // Verificar si el usuario está autenticado
        $usuario = Auth::user();
        if ($usuario->hasAnyRole(['Administrador', 'Auditor'])) {
            // Si es administrador o auditor puede ver todo y editar todo 
            $servicio_anexo_id = $slug;
            $servicioAnexo = ServicioAnexo::find($servicio_anexo_id);

            // Obtener la estación relacionada con el servicio anexo
            $estacion = Estacion::whereHas('estacionServicios', function ($query) use ($servicio_anexo_id) {
                $query->where('servicio_anexo_id', $servicio_anexo_id);
            })->first();

            // Ruta de la carpeta donde se guardan los archivos generados
            $folderPath = "servicios_anexo30/{$servicioAnexo->nomenclatura}/formatos_rellenados_anexo30";
            $existingFiles = [];

            // Verificar si la carpeta existe
            if (Storage::disk('public')->exists($folderPath)) {
                // Obtener los archivos existentes en la carpeta
                $files = Storage::disk('public')->files($folderPath);

                // Construir la lista de archivos con su URL
                foreach ($files as $file) {
                    $existingFiles[] = [
                        'name' => basename($file),
                        'url' => Storage::url($file)
                    ];
                }
            }

            $estaciones = Estacion::all();
            return view('armonia.servicio_anexo_30.datos_servicio_anexo.expediente', compact('estacion', 'estados', 'servicio_anexo_id', 'servicioAnexo', 'existingFiles', 'estaciones'));
        } else {
            // Verificar si el usuario tiene acceso al servicio
            $servicio_anexo_id = $slug;
            $servicioAnexo = ServicioAnexo::find($servicio_anexo_id);
            $validar_servicio = ($servicioAnexo->usuario_id == $usuario->id);

            if ($validar_servicio) {
                // Obtener la estación relacionada con el servicio anexo
                $estacion = Estacion::whereHas('estacionServicios', function ($query) use ($servicio_anexo_id) {
                    $query->where('servicio_anexo_id', $servicio_anexo_id);
                })->first();

                // Ruta de la carpeta donde se guardan los archivos generados
                $folderPath = "servicios_anexo30/{$servicioAnexo->nomenclatura}/formatos_rellenados_anexo30";
                $existingFiles = [];

                // Verificar si la carpeta existe
                if (Storage::disk('public')->exists($folderPath)) {
                    // Obtener los archivos existentes en la carpeta
                    $files = Storage::disk('public')->files($folderPath);

                    // Construir la lista de archivos con su URL
                    foreach ($files as $file) {
                        $existingFiles[] = [
                            'name' => basename($file),
                            'url' => Storage::url($file)
                        ];
                    }
                }

                return view('armonia.servicio_anexo_30.datos_servicio_anexo.expediente', compact('estacion', 'estados', 'servicio_anexo_id', 'servicioAnexo', 'existingFiles'));
            } else {
                return redirect()->route('servicio_anexo_30.datos_servicio_anexo.index')->with('error', 'Servicio no válido');
            }
        }
    }

    public function generateWord(Request $request)
    {

        try {
            // Obtener el ID de la estación desde la solicitud
            $idEstacion = $request->input('idestacion');

            // Buscar la estación por su ID y obtener los datos necesarios
            $estacion = Estacion::findOrFail($idEstacion);

            // Definir las reglas de validación
            $rules = [
                'nomenclatura' => 'required|string',
                'id_servicio' => 'required',
                'id_usuario' => 'required',
                'fecha_recepcion' => 'required|date',
                'cre' => 'required|string',
                'contacto' => 'required|string',
                'nom_repre' => 'required|string',
                'fecha_inspeccion' => 'required|date',
            ];

            // Validar los datos del formulario
            $data = $request->validate($rules);

            // Completar los datos necesarios para el procesamiento
            $data['numestacion'] = $estacion->num_estacion;
            $data['fecha_actual'] = Carbon::now()->format('d/m/Y');
            $data['razonsocial'] = $estacion->razon_social;
            $data['rfc'] = $estacion->rfc;
            $data['domicilio_fiscal'] = $estacion->domicilio_fiscal;
            $data['domicilio_estacion'] = $estacion->domicilio_estacion_servicio;
            $data['estado'] = $estacion->estado_republica_estacion;
            $data['telefono'] = $estacion->telefono;
            $data['correo'] = $estacion->correo_electronico;
            // dd($data);

            // Convertir las fechas al formato deseado
            $fechaInspeccion = Carbon::createFromFormat('Y-m-d', $data['fecha_inspeccion'])->format('d-m-Y');
            $fechaRecepcion = Carbon::createFromFormat('Y-m-d', $data['fecha_recepcion'])->format('d-m-Y');
            $fechaInspeccionAumentada = Carbon::createFromFormat('Y-m-d', $data['fecha_inspeccion'])->addYear()->format('d-m-Y');

            // Cargar las plantillas de Word
            $templatePaths = [
                'ORDEN DE TRABAJO.docx',
                'FORMATO PARA CONTRATO DE PRESTACIÓN DE SERVICIOS DE INSPECCIÓN DE LOS ANEXOS 30 Y 31 RESOLUCIÓN MISCELÁNEA FISCAL PARA 2024.docx',
                'FORMATO DE DETECCIÓN DE RIESGOS A LA IMPARCIALIDAD.docx',
                'PLAN DE INSPECCIÓN DE PROGRAMAS INFORMATICOS.docx',
                'PLAN DE INSPECCIÓN DE LOS SISTEMAS DE MEDICION.docx',
            ];

            // Definir la carpeta de destino
            $customFolderPath = "servicios_anexo30/{$data['nomenclatura']}";
            $subFolderPath = "{$customFolderPath}/expediente";

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
                $templateProcessor = new TemplateProcessor(storage_path("app/templates/formatos_anexo30/{$templatePath}"));

                // Reemplazar todos los marcadores con los datos del formulario
                foreach ($data as $key => $value) {
                    $templateProcessor->setValue($key, $value);
                    // Reemplazar fechas formateadas específicas
                    $templateProcessor->setValue('fecha_inspeccion', $fechaInspeccion);
                    $templateProcessor->setValue('fecha_recepcion', $fechaRecepcion);
                    $templateProcessor->setValue('fecha_inspeccion_modificada', $fechaInspeccionAumentada);
                }

                // Crear un nombre de archivo basado en la nomenclatura
                $fileName = pathinfo($templatePath, PATHINFO_FILENAME) . "_{$data['nomenclatura']}.docx";

                // Guardar la plantilla procesada en la carpeta de destino
                $templateProcessor->saveAs(storage_path("app/public/{$subFolderPath}/{$fileName}"));
            }

            $servicio = ServicioAnexo::firstOrNew(['id' => $data['id_servicio']]);
            $servicio->date_recepcion_at = $data['fecha_recepcion'];
            $servicio->date_inspeccion_at = $data['fecha_inspeccion'];
            $servicio->save();

            $expediente = Expediente_Servicio_Anexo_30::firstOrNew(['servicio_anexo_id' => $data['id_servicio']]);
            $expediente->rutadoc_estacion = $subFolderPath;
            $expediente->servicio_anexo_id = $data['id_servicio'];
            $expediente->usuario_id = $data['id_usuario'];
            $expediente->save();

            // Crear la lista de archivos generados con sus URLs
            $generatedFiles = array_map(function ($templatePath) use ($subFolderPath, $data) {
                $fileName = pathinfo($templatePath, PATHINFO_FILENAME) . "_{$data['nomenclatura']}.docx";
                return [
                    'name' => $fileName,
                    'url' => Storage::url("{$subFolderPath}/{$fileName}"),
                ];
            }, $templatePaths);

            // Retornar respuesta JSON con los archivos generados
            // Redireccionar a la vista con los archivos generados
            // Redireccionar a una ruta específica con los archivos generados
            // Redirigir a la vista deseada con los archivos generados
            return redirect()->route('expediente.anexo30', ['slug' => $data['id_servicio']])
                ->with('generatedFiles', $generatedFiles);


        } catch (\Exception $e) {
            // Capturar y registrar cualquier excepción ocurrida
            \Log::error("Error al generar documentos: " . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al procesar la solicitud. Por favor, intenta de nuevo más tarde.'], 500);
        }
    }

    public function listGeneratedFiles($nomenclatura)
    {
        $folderPath = "servicios_anexo30/{$nomenclatura}/documentos_rellenados/";
        $files = Storage::disk('public')->files($folderPath);

        $generatedFiles = array_map(function ($filePath) {
            return [
                'name' => basename($filePath),
                'url' => Storage::url($filePath),
            ];
        }, $files);

        return response()->json(['generatedFiles' => $generatedFiles]);
    }

    public function validarDatosExpediente($id)
    {
        try {
            // Cambiar la conexión a la base de datos 'segunda_db' y verificar la existencia de expedientes
            $existeExpediente = DB::connection('segunda_db')
                ->table('servicio_anexo_30')
                ->where('id', $id)
                ->exists();

            return response()->json([
                'exists' => $existeExpediente,
                'message' => $existeExpediente ? 'Expediente encontrado.' : 'No se encontró expediente.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Ocurrió un error al consultar el expediente.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function descargarWord(Request $request, $archivo, $nomenclatura)
    {
        // Ejemplo de construcción de la ruta del archivo
        $nomenclatura = strtoupper($nomenclatura); // Obtener la nomenclatura desde la ruta

        // Construir la ruta del archivo
        $rutaArchivo = storage_path("app/public/servicios_anexo30/{$nomenclatura}/documentos_rellenados/{$archivo}");

        // Verificar si el archivo existe antes de proceder
        if (file_exists($rutaArchivo)) {
            // Devolver el archivo para descargar
            return response()->download($rutaArchivo);
        } else {
            // Manejar el caso en que el archivo no exista
            abort(404, "El archivo no existe en la ruta especificada.");
        }
    }


    // Método para obtener los datos de una estación de servicio por su ID
    public function obtenerDatosEstacion($id)
    {
        try {
            $estacion = Estacion::findOrFail($id);

            $datosEstacion = [
                'numestacion' => $estacion->num_estacion,
                'razonsocial' => $estacion->razon_social,
                'rfc' => $estacion->rfc,
                'domicilio_fiscal' => $estacion->domicilio_fiscal,
                'telefono' => $estacion->telefono,
                'correo' => $estacion->correo_electronico,
                'cre' => $estacion->num_cre,
                'constancia' => $estacion->num_constancia,
                'domicilio_estacion' => $estacion->domicilio_estacion_servicio,
                'estado' => $estacion->estado_republica_estacion,
                'contacto' => $estacion->contacto,
                'nom_repre' => $estacion->nombre_representante_legal,
                // Agrega más campos según sea necesario
            ];

            return response()->json($datosEstacion);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los datos de la estación.'], 500);
        }
    }



}
