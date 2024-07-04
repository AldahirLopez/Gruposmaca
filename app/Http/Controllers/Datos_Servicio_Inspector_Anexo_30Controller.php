<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EstacionServicio;
use App\Models\ServicioAnexo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;

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
            $estacion = ServicioAnexo::find($servicio_anexo_id);
            $archivoAnexo = EstacionServicio::where('servicio_anexo_id', $servicio_anexo_id)->first();

            // Ruta de la carpeta donde se guardan los archivos generados
            $folderPath = "servicios_anexo30/{$estacion->nomenclatura}/formatos_rellenados_anexo30";
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

            $estaciones = EstacionServicio::all();
            return view('armonia.servicio_anexo_30.datos_servicio_anexo.expediente', compact('archivoAnexo', 'estados', 'servicio_anexo_id', 'estacion', 'existingFiles', 'estaciones'));
        } else {
            // Verificar si el usuario tiene acceso al servicio
            $servicio_anexo_id = $slug;
            $estacion = ServicioAnexo::find($servicio_anexo_id);
            $validar_servicio = ($estacion->usuario_id == $usuario->id);

            if ($validar_servicio) {
                $archivoAnexo = EstacionServicio::where('servicio_anexo_id', $servicio_anexo_id)->first();

                // Ruta de la carpeta donde se guardan los archivos generados
                $folderPath = "servicios_anexo30/{$estacion->nomenclatura}/formatos_rellenados_anexo30";
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

                return view('armonia.servicio_anexo_30.datos_servicio_anexo.expediente', compact('archivoAnexo', 'estados', 'servicio_anexo_id', 'estacion', 'existingFiles'));
            } else {
                return redirect()->route('servicio_anexo_30.datos_servicio_anexo.index')->with('error', 'Servicio no válido');
            }
        }
    }

    public function generateWord(Request $request)
    {
        try {
            // Validar los datos del formulario
            $data = $request->validate([
                'numestacion' => 'required',
                'nomenclatura' => 'required',
                'id_servicio' => 'required',
                'id_usuario' => 'required',
                'fecha_actual' => 'required',
                'razonsocial' => 'required|string|max:255',
                'rfc' => 'required|string|max:255',
                'domicilio_fiscal' => 'required|string|max:255',
                'telefono' => 'required|string|max:255',
                'correo' => 'required|string|email|max:255',
                'fecha_recepcion' => 'required',
                'cre' => 'required|string|max:255',
                'constancia' => 'nullable|string|max:255',
                'domicilio_estacion' => 'required|string|max:255',
                'estado' => 'required|string|max:255',
                'contacto' => 'required|string|max:255',
                'nom_repre' => 'required|string|max:255',
                'fecha_inspeccion' => 'required',
            ]);

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
            $subFolderPath = "{$customFolderPath}/documentos_rellenados/";

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

                // Guardar la plantilla procesada
                $templateProcessor->saveAs(storage_path("app/public/{$subFolderPath}/{$fileName}"));
            }

            // Recuperar o crear el registro en EstacionServicio basado en 'servicio_anexo_id'
            $estacionServicio = EstacionServicio::firstOrNew(['servicio_anexo_id' => $data['id_servicio']]);

            // Asignar los datos del formulario al modelo
            $estacionServicio->Num_Estacion = $data['numestacion'];
            $estacionServicio->Razon_Social = $data['razonsocial'];
            $estacionServicio->RFC = $data['rfc'];
            $estacionServicio->Domicilio_Fiscal = $data['domicilio_fiscal'];
            $estacionServicio->Telefono = $data['telefono'];
            $estacionServicio->Correo = $data['correo'];
            $estacionServicio->Fecha_Recepcion_Solicitud = $data['fecha_recepcion'] ?? null;
            $estacionServicio->Num_CRE = $data['cre'];
            $estacionServicio->Num_Constancia = $data['constancia'] ?? null;
            $estacionServicio->Domicilio_Estacion_Servicio = $data['domicilio_estacion'];
            $estacionServicio->Estado_Republica_Estacion = $data['estado'];
            $estacionServicio->Contacto = $data['contacto'];
            $estacionServicio->Nombre_Representante_Legal = $data['nom_repre'];
            $estacionServicio->Fecha_Inspeccion = $data['fecha_inspeccion'] ?? null;

            // Asignar las claves foráneas
            $estacionServicio->usuario_id = $data['id_usuario'];
            $estacionServicio->servicio_anexo_id = $data['id_servicio'];

            // Guardar el objeto en la base de datos
            $estacionServicio->save();

            // Crear la lista de archivos generados con sus URLs
            $generatedFiles = array_map(function ($templatePath) use ($subFolderPath, $data) {
                $fileName = pathinfo($templatePath, PATHINFO_FILENAME) . "_{$data['nomenclatura']}.docx";
                return [
                    'name' => $fileName,
                    'url' => Storage::url("{$subFolderPath}/{$fileName}"),
                ];
            }, $templatePaths);

            // Retornar respuesta JSON con los archivos generados
            return response()->json(['generatedFiles' => $generatedFiles]);

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
        // Busca el registro en la base de datos
        $registro = EstacionServicio::where('servicio_anexo_id', $id)->first();

        // Responde con datos JSON
        if ($registro) {
            return response()->json(['exists' => true, 'data' => $registro]);
        } else {
            return response()->json(['exists' => false]);
        }
    }

    public function descargarWord(Request $request, $archivo, $estacion)
    {
        // Ejemplo de construcción de la ruta del archivo
        $nomenclatura = strtoupper($estacion); // Obtener la nomenclatura desde la ruta

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

    public function obtenerDatosEstacion($id)
    {
        try {
            $estacion = EstacionServicio::findOrFail($id);

            $datosEstacion = [
                'id_estacion' => $estacion->id,
                'razonsocial' => $estacion->Razon_Social,
                'rfc' => $estacion->RFC,
                'numestacion' => $estacion->Num_Estacion,
                'domicilio_fiscal' => $estacion->Domicilio_Fiscal,
                'telefono' => $estacion->Telefono,
                'correo' => $estacion->Correo,
                'fecha_recepcion' => $estacion->Fecha_Recepcion_Solicitud,
                'cre' => $estacion->Num_CRE,
                'constancia' => $estacion->Num_Constancia,
                'domicilio_estacion' => $estacion->Domicilio_Estacion_Servicio,
                'estado' => $estacion->Estado_Republica_Estacion,
                'contacto' => $estacion->Contacto,
                'nom_repre' => $estacion->Nombre_Representante_Legal,
                'fecha_inspeccion' => $estacion->Fecha_Inspeccion,
                // Agrega más campos según sea necesario
            ];

            return response()->json($datosEstacion);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener los datos de la estación.'], 500);
        }
    }
}
