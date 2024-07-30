<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Documento_Servicio_Anexo;
use App\Models\Estacion;
use App\Models\Estacion_Servicio;
use App\Models\Expediente_Servicio_Anexo_30;
use App\Models\ServicioAnexo;
use App\Models\Usuario_Estacion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\DB;

class Datos_Servicio_Inspector_Anexo_30Controller extends Controller
{

    function __construct()
    {

        //Expediente 
        $this->middleware('permission:Generar-expediente-anexo_30', ['only' => ['ExpedienteInspectorAnexo30', 'generateWord']]);
        $this->middleware('permission:Descargar-documentos-expediente-anexo_30', ['only' => ['descargarWord']]);
        //Documentacion
        $this->middleware('permission:Generar-documentacion-anexo_30', ['only' => ['DocumentacionAnexo', 'storeanexo']]);
        //Dictamenes
        $this->middleware('permission:Generar-dictamenes-anexo', ['only' => ['guardarDictamenes']]);
    }

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
            // Verificar todos los datos de la solicitud
            // dd($request->all());

            // Obtener el ID de la estación desde la solicitud
            $idEstacion = $request->input('idestacion');

            // Buscar la estación por su ID y obtener los datos necesarios
            $estacion = Estacion::findOrFail($idEstacion);

            // Definir las reglas de validación
            $rules = [
                'nomenclatura' => 'required',
                'idestacion' => 'required',
                'id_servicio' => 'required',
                'id_usuario' => 'required',
                'fecha_recepcion' => 'required|date',
                'cre' => 'nullable',
                'contacto' => 'nullable',
                'nom_repre' => 'nullable',
                'constancia' => 'nullable',
                'fecha_inspeccion' => 'required|date',
                'cantidad' => 'required',

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

            // Si algún campo opcional no está en los datos validados, úsalo de la base de datos
            $data['cre'] = $data['cre'] ?? $estacion->num_cre ?? '';
            $data['contacto'] = $data['contacto'] ?? $estacion->contacto ?? '';
            $data['nom_repre'] = $data['nom_repre'] ?? $estacion->nombre_representante_legal ?? '';
            $data['constancia'] = $data['constancia'] ?? $estacion->num_constancia ?? '';

            //Calculo de precio con iva y el 50% para el contrato

            $data['iva'] = $data['cantidad'] * 0.16;
            $data['total'] = $data['cantidad'] + $data['iva'];
            $data['total_mitad'] = $data['total'] * 0.50;
            $data['total_restante'] = $data['total'] - $data['total_mitad'];

            // Formateando los valores
            $data['cantidad'] = number_format($data['cantidad'], 2, '.', ',');
            $data['iva'] = number_format($data['iva'], 2, '.', ',');
            $data['total'] = number_format($data['total'], 2, '.', ',');
            $data['total_mitad'] = number_format($data['total_mitad'], 2, '.', ',');
            $data['total_restante'] = number_format($data['total_restante'], 2, '.', ',');



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

            $estacion->num_cre = $data['cre'];
            $estacion->num_constancia = $data['constancia'];
            $estacion->contacto = $data['contacto'];
            $estacion->nombre_representante_legal = $data['nom_repre'];
            $estacion->save();

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
        $folderPath = "servicios_anexo30/{$nomenclatura}/expediente/";
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
                ->table('expediente_servicio_anexo_30')
                ->where('servicio_anexo_id', $id)
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
        $rutaArchivo = storage_path("app/public/servicios_anexo30/{$nomenclatura}/expediente/{$archivo}");

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

    //Dictamenes
    public function guardarDictamenesInformatico(Request $request)
    {
        try {
            // Obtener el ID de servicio desde la solicitud
            $idServicio = $request->input('id_servicio');

            $idUsuario = $request->input('id_usuario');

            $idEstacion = $request->input('idestacion');

            // Buscar el servicio anexo por su ID y obtener los datos necesarios
            $servicio = ServicioAnexo::findOrFail($idServicio);

            $usuario = User::findOrFail($idUsuario);

            $estacion = Estacion::findOrFail($idEstacion);

            // Definir las reglas de validación
            $rules = [
                'nomenclatura' => 'required|string',
                'id_servicio' => 'required',
                'id_usuario' => 'required',
                'nom_repre' => 'required',
                'proveedor' => 'required',
                'rfc_proveedor' => 'required',
                'software' => 'required',
                'version' => 'required',

                'opcion1' => 'required', // Asegúrate de ajustar las reglas de validación según tu necesidad
                'opcion2' => 'required', // Asegúrate de ajustar las reglas de validación según tu necesidad
                'opcion3' => 'required', // Asegúrate de ajustar las reglas de validación según tu necesidad
                'opcion4' => 'required', // Asegúrate de ajustar las reglas de validación según tu necesidad
                'opcion5' => 'required', // Asegúrate de ajustar las reglas de validación según tu necesidad
                'opcion6' => 'required', // Asegúrate de ajustar las reglas de validación según tu necesidad
                'detalleOpinion1' => 'required', // Texto
                'recomendaciones1' => 'required', // Texto
                'detalleOpinion2' => 'required', // Texto
                'recomendaciones2' => 'required', // Texto
                'detalleOpinion3' => 'required', // Texto
                'recomendaciones3' => 'required', // Texto
                'detalleOpinion4' => 'required', // Texto
                'recomendaciones4' => 'required', // Texto
                'detalleOpinion5' => 'required', // Texto
                'recomendaciones5' => 'required', // Texto

            ];

            // Validar los datos del formulario
            $data = $request->validate($rules);
            // Obtener las fechas desde el servicio y formatearlas correctamente
            $fechaInspeccion = Carbon::parse($servicio->date_inspeccion_at)->format('d-m-Y');
            $fechaRecepcion = Carbon::parse($servicio->date_recepcion_at)->format('d-m-Y');
            $fechaInspeccionAumentada = Carbon::parse($servicio->date_inspeccion_at)->addYear()->format('d-m-Y');

            // Completar los datos necesarios para el procesamiento
            $data['fecha_inspeccion'] = $fechaInspeccion;
            $data['fecha_recepcion'] = $fechaRecepcion;
            $data['fecha_inspeccion_modificada'] = $fechaInspeccionAumentada;
            $data['nom_verificador'] = $usuario->name;
            $data['razonsocial'] = $estacion->razon_social;
            $data['direccion_estacion'] = $estacion->domicilio_estacion_servicio;
            $data['telefono'] = $estacion->telefono;
            $data['correo'] = $estacion->correo_electronico;

            // Cargar las plantillas de Word
            $templatePaths = [
                'DICTAMEN TECNICO DE PROGRAMAS INFORMATICOS.docx',
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

                    // Establecer los valores correctos basados en la opción seleccionada
                    switch ($data['opcion1']) {
                        case 'cumple':
                            $templateProcessor->setValue('si', 'X');
                            $templateProcessor->setValue('no', ' ');
                            $templateProcessor->setValue('noaplica', ' ');
                            break;
                        case 'no_cumple':
                            $templateProcessor->setValue('si', ' ');
                            $templateProcessor->setValue('no', 'X');
                            $templateProcessor->setValue('noaplica', ' ');
                            break;
                        case 'no_aplica':
                            $templateProcessor->setValue('si', ' ');
                            $templateProcessor->setValue('no', ' ');
                            $templateProcessor->setValue('noaplica', 'X');
                            break;
                        default:
                            // Manejar cualquier otro caso aquí si es necesario
                            break;
                    }

                    // Establecer los valores correctos basados en la opción seleccionada
                    switch ($data['opcion2']) {
                        case 'cumple':
                            $templateProcessor->setValue('si2', 'X');
                            $templateProcessor->setValue('no2', ' ');
                            $templateProcessor->setValue('noaplica2', ' ');
                            break;
                        case 'no_cumple':
                            $templateProcessor->setValue('si2', ' ');
                            $templateProcessor->setValue('no2', 'X');
                            $templateProcessor->setValue('noaplica2', ' ');
                            break;
                        case 'no_aplica':
                            $templateProcessor->setValue('si2', ' ');
                            $templateProcessor->setValue('no2', ' ');
                            $templateProcessor->setValue('noaplica2', 'X');
                            break;
                        default:
                            // Manejar cualquier otro caso aquí si es necesario
                            break;
                    }
                    // Establecer los valores correctos basados en la opción seleccionada
                    switch ($data['opcion3']) {
                        case 'cumple':
                            $templateProcessor->setValue('si3', 'X');
                            $templateProcessor->setValue('no3', ' ');
                            $templateProcessor->setValue('noaplica3', ' ');
                            break;
                        case 'no_cumple':
                            $templateProcessor->setValue('si3', ' ');
                            $templateProcessor->setValue('no3', 'X');
                            $templateProcessor->setValue('noaplica3', ' ');
                            break;
                        case 'no_aplica':
                            $templateProcessor->setValue('si3', ' ');
                            $templateProcessor->setValue('no3', ' ');
                            $templateProcessor->setValue('noaplica3', 'X');
                            break;
                        default:
                            // Manejar cualquier otro caso aquí si es necesario
                            break;
                    }
                    // Establecer los valores correctos basados en la opción seleccionada
                    switch ($data['opcion4']) {
                        case 'cumple':
                            $templateProcessor->setValue('si4', 'X');
                            $templateProcessor->setValue('no4', ' ');
                            $templateProcessor->setValue('noaplica4', ' ');
                            break;
                        case 'no_cumple':
                            $templateProcessor->setValue('si4', ' ');
                            $templateProcessor->setValue('no4', 'X');
                            $templateProcessor->setValue('noaplica4', ' ');
                            break;
                        case 'no_aplica':
                            $templateProcessor->setValue('si4', ' ');
                            $templateProcessor->setValue('no4', ' ');
                            $templateProcessor->setValue('noaplica4', 'X');
                            break;
                        default:
                            // Manejar cualquier otro caso aquí si es necesario
                            break;
                    }
                    // Establecer los valores correctos basados en la opción seleccionada
                    switch ($data['opcion5']) {
                        case 'cumple':
                            $templateProcessor->setValue('si5', 'X');
                            $templateProcessor->setValue('no5', ' ');
                            $templateProcessor->setValue('noaplica5', ' ');
                            break;
                        case 'no_cumple':
                            $templateProcessor->setValue('si5', ' ');
                            $templateProcessor->setValue('no5', 'X');
                            $templateProcessor->setValue('noaplica5', ' ');
                            break;
                        case 'no_aplica':
                            $templateProcessor->setValue('si5', ' ');
                            $templateProcessor->setValue('no5', ' ');
                            $templateProcessor->setValue('noaplica5', 'X');
                            break;
                        default:
                            // Manejar cualquier otro caso aquí si es necesario
                            break;
                    }
                    // Establecer los valores correctos basados en la opción seleccionada
                    switch ($data['opcion6']) {
                        case 'cumple':
                            $templateProcessor->setValue('si6', 'X');
                            $templateProcessor->setValue('no6', ' ');
                            break;
                        case 'no_cumple':
                            $templateProcessor->setValue('si6', ' ');
                            $templateProcessor->setValue('no6', 'X');
                            break;
                        default:
                            // Manejar cualquier otro caso aquí si es necesario
                            break;
                    }
                }

                // Crear un nombre de archivo basado en la nomenclatura
                $fileName = pathinfo($templatePath, PATHINFO_FILENAME) . "_{$data['nomenclatura']}.docx";

                // Guardar la plantilla procesada en la carpeta de destino
                $templateProcessor->saveAs(storage_path("app/public/{$subFolderPath}/{$fileName}"));
            }

            // Crear la lista de archivos generados con sus URLs
            $generatedFiles = array_map(function ($templatePath) use ($subFolderPath, $data) {
                $fileName = pathinfo($templatePath, PATHINFO_FILENAME) . "_{$data['nomenclatura']}.docx";
                return [
                    'name' => $fileName,
                    'url' => Storage::url("{$subFolderPath}/{$fileName}"),
                ];
            }, $templatePaths);

            // Retornar respuesta con los archivos generados
            return redirect()->route('expediente.anexo30', ['slug' => $data['id_servicio']])
                ->with('generatedFiles', $generatedFiles);
        } catch (\Exception $e) {
            // Capturar y registrar cualquier excepción ocurrida
            \Log::error("Error al generar documentos: " . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al procesar la solicitud. Por favor, intenta de nuevo más tarde.'], 500);
        }
    }

    public function guardarDictamenesMedicion(Request $request)
    {
        try {
            // Obtener el ID de servicio desde la solicitud
            $idServicio = $request->input('id_servicio');

            $idUsuario = $request->input('id_usuario');

            $idEstacion = $request->input('idestacion');

            // Buscar el servicio anexo por su ID y obtener los datos necesarios
            $servicio = ServicioAnexo::findOrFail($idServicio);

            $usuario = User::findOrFail($idUsuario);

            $estacion = Estacion::findOrFail($idEstacion);

            // Definir las reglas de validación
            $rules = [
                'nomenclatura' => 'required|string',
                'id_servicio' => 'required',
                'id_usuario' => 'required',
                'nom_repre' => 'required',
                'proveedor' => 'required',
                'rfc_proveedor' => 'required',
                'software' => 'required',
                'version' => 'required',

                'opcion1' => 'required', // Asegúrate de ajustar las reglas de validación según tu necesidad
                'opcion2' => 'required', // Asegúrate de ajustar las reglas de validación según tu necesidad
                'opcion3' => 'required', // Asegúrate de ajustar las reglas de validación según tu necesidad
                'opcion4' => 'required', // Asegúrate de ajustar las reglas de validación según tu necesidad
                'detalleOpinion1' => 'required', // Texto
                'recomendaciones1' => 'required', // Texto
                'detalleOpinion2' => 'required', // Texto
                'recomendaciones2' => 'required', // Texto
                'detalleOpinion3' => 'required', // Texto
                'recomendaciones3' => 'required', // Texto
                'detalleOpinion4' => 'required', // Texto
                'recomendaciones4' => 'required', // Texto

            ];

            // Validar los datos del formulario
            $data = $request->validate($rules);
            // Obtener las fechas desde el servicio y formatearlas correctamente
            $fechaInspeccion = Carbon::parse($servicio->date_inspeccion_at)->format('d-m-Y');
            $fechaRecepcion = Carbon::parse($servicio->date_recepcion_at)->format('d-m-Y');
            $fechaInspeccionAumentada = Carbon::parse($servicio->date_inspeccion_at)->addYear()->format('d-m-Y');

            // Completar los datos necesarios para el procesamiento
            $data['fecha_inspeccion'] = $fechaInspeccion;
            $data['fecha_recepcion'] = $fechaRecepcion;
            $data['fecha_inspeccion_modificada'] = $fechaInspeccionAumentada;
            $data['nom_verificador'] = $usuario->name;
            $data['razonsocial'] = $estacion->razon_social;
            $data['direccion_estacion'] = $estacion->domicilio_estacion_servicio;
            $data['telefono'] = $estacion->telefono;
            $data['correo'] = $estacion->correo_electronico;

            // Cargar las plantillas de Word
            $templatePaths = [
                'DICTAMEN TECNICO DE SISTEMAS DE MEDICION.docx',
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

                    // Establecer los valores correctos basados en la opción seleccionada
                    switch ($data['opcion1']) {
                        case 'cumple':
                            $templateProcessor->setValue('si', 'X');
                            $templateProcessor->setValue('no', ' ');
                            $templateProcessor->setValue('noaplica', ' ');
                            break;
                        case 'no_cumple':
                            $templateProcessor->setValue('si', ' ');
                            $templateProcessor->setValue('no', 'X');
                            $templateProcessor->setValue('noaplica', ' ');
                            break;
                        case 'no_aplica':
                            $templateProcessor->setValue('si', ' ');
                            $templateProcessor->setValue('no', ' ');
                            $templateProcessor->setValue('noaplica', 'X');
                            break;
                        default:
                            // Manejar cualquier otro caso aquí si es necesario
                            break;
                    }

                    // Establecer los valores correctos basados en la opción seleccionada
                    switch ($data['opcion2']) {
                        case 'cumple':
                            $templateProcessor->setValue('si2', 'X');
                            $templateProcessor->setValue('no2', ' ');
                            $templateProcessor->setValue('noaplica2', ' ');
                            break;
                        case 'no_cumple':
                            $templateProcessor->setValue('si2', ' ');
                            $templateProcessor->setValue('no2', 'X');
                            $templateProcessor->setValue('noaplica2', ' ');
                            break;
                        case 'no_aplica':
                            $templateProcessor->setValue('si2', ' ');
                            $templateProcessor->setValue('no2', ' ');
                            $templateProcessor->setValue('noaplica2', 'X');
                            break;
                        default:
                            // Manejar cualquier otro caso aquí si es necesario
                            break;
                    }
                    // Establecer los valores correctos basados en la opción seleccionada
                    switch ($data['opcion3']) {
                        case 'cumple':
                            $templateProcessor->setValue('si3', 'X');
                            $templateProcessor->setValue('no3', ' ');
                            $templateProcessor->setValue('noaplica3', ' ');
                            break;
                        case 'no_cumple':
                            $templateProcessor->setValue('si3', ' ');
                            $templateProcessor->setValue('no3', 'X');
                            $templateProcessor->setValue('noaplica3', ' ');
                            break;
                        case 'no_aplica':
                            $templateProcessor->setValue('si3', ' ');
                            $templateProcessor->setValue('no3', ' ');
                            $templateProcessor->setValue('noaplica3', 'X');
                            break;
                        default:
                            // Manejar cualquier otro caso aquí si es necesario
                            break;
                    }
                    // Establecer los valores correctos basados en la opción seleccionada
                    switch ($data['opcion4']) {
                        case 'cumple':
                            $templateProcessor->setValue('si4', 'X');
                            $templateProcessor->setValue('no4', ' ');
                            $templateProcessor->setValue('noaplica4', ' ');
                            break;
                        case 'no_cumple':
                            $templateProcessor->setValue('si4', ' ');
                            $templateProcessor->setValue('no4', 'X');
                            $templateProcessor->setValue('noaplica4', ' ');
                            break;
                        case 'no_aplica':
                            $templateProcessor->setValue('si4', ' ');
                            $templateProcessor->setValue('no4', ' ');
                            $templateProcessor->setValue('noaplica4', 'X');
                            break;
                        default:
                            // Manejar cualquier otro caso aquí si es necesario
                            break;
                    }
                    // Establecer los valores correctos basados en la opción seleccionada
                    switch ($data['opcion6']) {
                        case 'cumple':
                            $templateProcessor->setValue('si6', 'X');
                            $templateProcessor->setValue('no6', ' ');
                            break;
                        case 'no_cumple':
                            $templateProcessor->setValue('si6', ' ');
                            $templateProcessor->setValue('no6', 'X');
                            break;
                        default:
                            // Manejar cualquier otro caso aquí si es necesario
                            break;
                    }
                }

                // Crear un nombre de archivo basado en la nomenclatura
                $fileName = pathinfo($templatePath, PATHINFO_FILENAME) . "_{$data['nomenclatura']}.docx";

                // Guardar la plantilla procesada en la carpeta de destino
                $templateProcessor->saveAs(storage_path("app/public/{$subFolderPath}/{$fileName}"));
            }

            // Crear la lista de archivos generados con sus URLs
            $generatedFiles = array_map(function ($templatePath) use ($subFolderPath, $data) {
                $fileName = pathinfo($templatePath, PATHINFO_FILENAME) . "_{$data['nomenclatura']}.docx";
                return [
                    'name' => $fileName,
                    'url' => Storage::url("{$subFolderPath}/{$fileName}"),
                ];
            }, $templatePaths);

            // Retornar respuesta con los archivos generados
            return redirect()->route('expediente.anexo30', ['slug' => $data['id_servicio']])
                ->with('generatedFiles', $generatedFiles);
        } catch (\Exception $e) {
            // Capturar y registrar cualquier excepción ocurrida
            \Log::error("Error al generar documentos: " . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al procesar la solicitud. Por favor, intenta de nuevo más tarde.'], 500);
        }
    }

    public function documentacion(Request $request){
        try {
            if ($request->has('id')) {
                $id = $request->input('id');
                $servicio = ServicioAnexo::findOrFail($id);
                $nomenclatura = str_replace([' ', '.'], '_', $servicio->nomenclatura);


                return view('armonia.servicio_anexo_30.datos_servicio_anexo.documentos', compact('id', 'servicio'));
            } else {
                return redirect()->route('armonia.servicio_anexo_30.datos_servicio_anexo.documentos')->with('error', 'No se proporcionó un ID de estación.');
            }
        } catch (\Exception $e) {
            return redirect()->route('servicio_inspector_anexo_30.index')->with('error', 'Error al obtener la documentación: ' . $e->getMessage());
        }
    }


    //LISTA DE DOCUMENTOS REQUERIDOS SITEMAS DE MEDICION ANEXO 30 y 31 RMF 2024
    public function DocumentacionAnexo(Request $request)
    {
        try {
            if ($request->has('id')) {
                $id = $request->input('id');
                $servicio = ServicioAnexo::findOrFail($id);
                $nomenclatura = str_replace([' ', '.'], '_', $servicio->nomenclatura);
                $customFolderPath = "servicios_anexo30/{$nomenclatura}/documentacion/medicion";

                $requiredDocuments = [
                    ['descripcion' => 'Dictámenes de calibración de dispensarios (primero y segundo semestre del año a inspeccionar)', 'codigo' => '', 'tipo' => 'Documental','id'=>1],
                    ['descripcion' => 'Orden de Servicio de la última actualización de dispensarios', 'codigo' => '', 'tipo' => 'Documental','id'=>2],
                    ['descripcion' => 'Aprobación de modelo prototipo (dispensarios)', 'codigo' => '', 'tipo' => 'Documental','id'=>3],
                    ['descripcion' => 'DGN de certificado de producto de software de dispensarios', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>4],
                    ['descripcion' => 'DGN de resolución favorable de actualización de dispensarios (si aplica)', 'codigo' => '', 'tipo' => 'Documental','id'=>5],
                    ['descripcion' => 'Modelo, marca y capacidad de tanques', 'codigo' => '', 'tipo' => 'Documental','id'=>6],                
                    ['descripcion' => 'Plano Arquitectónico de la Estación de servicio', 'codigo' => '', 'tipo' => 'Documental','id'=>7],
                    ['descripcion' => 'Plano Mecánico de la Estación de servicio', 'codigo' => '', 'tipo' => 'Documental','id'=>8],
                    ['descripcion' => 'Dictamen de inspección NOM-005-SCFI-2017', 'codigo' => '', 'tipo' => 'Documental','id'=>9],
                    ['descripcion' => 'Dictamen de inspección NOM-185-SCFI-2017', 'codigo' => '', 'tipo' => 'Documental','id'=>10],
                    ['descripcion' => 'Fichas técnicas y/o manuales de equipos de medición (sondas, dispensarios y consola de Telemedicion)', 'codigo' => '', 'tipo' => 'Documental','id'=>11],
                    ['descripcion' => 'Informes de calibración de sondas de medición en magnitudes: nivel y temperatura', 'codigo' => '', 'tipo' => 'Documental','id'=>12],
                    ['descripcion' => 'Verificar que la consola cuente con contraseña', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>13],
                    ['descripcion' => 'Certificado de calibración de tanques', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>14],
                    ['descripcion' => 'Tablas de cubicación de tanques ', 'codigo' => '', 'tipo' => 'Fotos','id'=>15],
                    ['descripcion' => 'Sistema de Gestión de Medición (SGM) digital: Manual, procedimientos y formatos', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>16],
                    ['descripcion' => 'Constancia de capacitación al personal involucrado en las actividades del SGM', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>17],
                    ['descripcion' => 'Certificados de calibración vigentes de equipos de medición manual para la correcta verificación de los equipos automáticos(Cinta petrolera con plomada, Termómetro electrónico portátil, Jarra patrón)', 'codigo' => '', 'tipo' => 'Fotos','id'=>18],
                    ['descripcion' => 'Reportes de laboratorio de la calidad del petrolífero correspondientes al primero y segundo semestre del año', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>19],
                    
                ];

                $documentos = [];
                if (Storage::disk('public')->exists($customFolderPath)) {
                    $archivos = Storage::disk('public')->files($customFolderPath);
                    foreach ($archivos as $archivo) {
                        $nombreArchivo = pathinfo($archivo, PATHINFO_FILENAME);
                        $extension = pathinfo($archivo, PATHINFO_EXTENSION);
                        $rutaArchivo = Storage::url($archivo);

                        // Extraer referencia y nombre del archivo
                        $partes = explode('-', $nombreArchivo, 3);
                        $referencia = $partes[0] ?? '';
                        $nombre = $partes[1] ?? '';

                        $documentos[] = (object) [
                            'nombre' => $nombre,
                            'referencia' => $referencia,
                            'ruta' => $rutaArchivo,
                            'extension' => $extension
                        ];
                    }
                }

                return view('armonia.servicio_anexo_30.datos_servicio_anexo.documentacion_medicion', compact('requiredDocuments', 'documentos', 'id', 'servicio'));
            } else {
                return redirect()->route('armonia.servicio_anexo_30.datos_servicio_anexo.documentacion_medicion')->with('error', 'No se proporcionó un ID de estación.');
            }
        } catch (\Exception $e) {
            return redirect()->route('servicio_inspector_anexo_30.index')->with('error', 'Error al obtener la documentación: ' . $e->getMessage());
        }
    }



    public function storeanexo(Request $request)
    {
        $data = $request->validate([
            'rutadoc_estacion' => 'required|file',
            'servicio_id' => 'required',
            'nomenclatura' => 'required',
            'nombre' => 'required',
            'referencia' => 'required',
            'id_documento'=>'required'
        ]);

        try {
            $documento = Documento_Servicio_Anexo::firstOrNew(['servicio_id' => $data['servicio_id']]);

            if ($request->hasFile('rutadoc_estacion')) {
                $archivoSubido = $request->file('rutadoc_estacion');
                $nombreArchivoPersonalizado = $data['referencia'] . '-' . $data['nombre'] . '-' . $data['id_documento']. '.' . $archivoSubido->getClientOriginalExtension();

                $nomenclatura = $data['nomenclatura'];
                $customFolderPath = "servicios_anexo30/{$nomenclatura}/documentacion/medicion";

                if (!Storage::disk('public')->exists($customFolderPath)) {
                    Storage::disk('public')->makeDirectory($customFolderPath);
                }

                // Verificar si el archivo ya existe y eliminarlo
                $archivosExistentes = Storage::disk('public')->files($customFolderPath);
                $nombreSinReferenciaYId = "{$data['nombre']}";

                foreach ($archivosExistentes as $archivoExistente) {
                    // Extraer la parte del nombre del archivo existente
                    $partesArchivoExistente = explode('-', basename($archivoExistente, '.' . pathinfo($archivoExistente, PATHINFO_EXTENSION)));
                    if (count($partesArchivoExistente) > 1) {
                        $nombreExistente = $partesArchivoExistente[1];
                        if ($nombreExistente === $nombreSinReferenciaYId) {
                            Storage::disk('public')->delete($archivoExistente);
                        }
                    }
                }

                $rutaArchivo = $archivoSubido->storeAs("public/{$customFolderPath}", $nombreArchivoPersonalizado);

                $documento->rutadoc_estacion = "servicios_anexo30/{$nomenclatura}/documentacion";
            }

            $documento->servicio_id = $data['servicio_id'];
            $documento->usuario_id = Auth::id();
            $documento->save();

            return redirect()->route('documentacion_anexo_medicion', ['id' => $data['servicio_id']])->with('success', 'Documento guardado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('documentacion_anexo_medicion', ['id' => $data['servicio_id']])->with('error', 'Documento no guardado exitosamente.');
        }
    }


    public function generarSistemaMedicion(Request $request){ 

       
        $data = $request->validate([
            'servicio_id' => 'required',
            'nomenclatura' => 'required',
        ]);

        

        $servicio = ServicioAnexo::findOrFail($data['servicio_id']);
       
        $estacion=$servicio->estacionServicios()->where('servicio_anexo_id',$servicio->id)->first();

        $customFolderPath = "servicios_anexo30/{$data['nomenclatura']}/documentacion/medicion";


        $documentos = [];
                if (Storage::disk('public')->exists($customFolderPath)) {
                    $archivos = Storage::disk('public')->files($customFolderPath);
                    foreach ($archivos as $archivo) {
                        $nombreArchivo = pathinfo($archivo, PATHINFO_FILENAME);
                        $extension = pathinfo($archivo, PATHINFO_EXTENSION);
                        $rutaArchivo = Storage::url($archivo);

                        // Extraer referencia y nombre del archivo
                        $partes = explode('-', $nombreArchivo, 3);
                        $referencia = $partes[0] ?? '';
                        $nombre = $partes[1] ?? '';
                        $id = $partes[2] ?? '';

                        $documentos[] = (object) [
                            'id'=>$id,
                            'nombre' => $nombre,
                            'referencia' => $referencia,
                            'ruta' => $rutaArchivo,
                            'extension' => $extension
                        ];

                        
                    }
                }
              
        
            $codigos_documentos = [];
            foreach ($documentos as $documento) {

                $codigos_documentos[] = [
                    'name' => 'cod_' . $documento->id,
                    'codigo'=>$documento->referencia 
                ];

                
            }
           

            $templatePaths = [             
                'REQUISITOS SISTEMA DE MEDICION ESTACIONES.docx', 
            ];

            //Carpeta destino del documento
            $customFolderPath = "servicios_anexo30/{$data['nomenclatura']}/documentacion/sistema_medicion";
           
            if (!Storage::disk('public')->exists($customFolderPath)) {
                Storage::disk('public')->makeDirectory($customFolderPath);
            }

            foreach ($templatePaths as $templatePath) {
                $templateProcessor = new TemplateProcessor(storage_path("app/templates/formatos_anexo30/{$templatePath}"));

                for ($i = 0; $i < count($codigos_documentos); $i++) {
                       
                    $templateProcessor->setValue($codigos_documentos[$i]['name'],$codigos_documentos[$i]['codigo'] );
                }
                $templateProcessor->setValue('razon_social',$estacion->razon_social);
                $templateProcessor->setValue('cre',$estacion->num_cre);
              
             
                // Crear un nombre de archivo basado en la nomenclatura
                $fileName = pathinfo($templatePath, PATHINFO_FILENAME) . "_{$data['nomenclatura']}.docx";

                // Guardar la plantilla procesada en la carpeta de destino
                $templateProcessor->saveAs(storage_path("app/public/{$customFolderPath}/{$fileName}"));
                $rutaArchivo=storage_path("app/public/{$customFolderPath}/{$fileName}");
            }
            

            if (file_exists($rutaArchivo)) {
                // Devolver el archivo para descargar
                return response()->download($rutaArchivo);
            } else {
                // Manejar el caso en que el archivo no exista
                abort(404, "El archivo no existe en la ruta especificada.");
            }

           


    }


    //LISTA DE DOCUMENTOS GENERALES REQUERIDOS ANEZO 30 Y 31 RMF 2024 
    public function documentosGenerales(Request $request){
       
        try {
            if ($request->has('id')) {
                $id = $request->input('id');
                $servicio = ServicioAnexo::findOrFail($id);
                $nomenclatura = str_replace([' ', '.'], '_', $servicio->nomenclatura);
                $customFolderPath = "servicios_anexo30/{$nomenclatura}/documentacion/generales";

                $requiredDocuments = [
                    ['descripcion' => 'Cedula de Identificación Fiscal de la Empresa (CIF, ALTA SAT)', 'codigo' => '', 'tipo' => 'Documental','id'=>1],
                    ['descripcion' => 'Cedula de Identificación Fiscal del Representante Legal (CIF, ALTA SAT)', 'codigo' => '', 'tipo' => 'Documental','id'=>2],
                    ['descripcion' => 'INE del representante legal', 'codigo' => '', 'tipo' => 'Documental','id'=>3],
                    ['descripcion' => 'Permiso de la Cre', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>4],                  
                ];

                $documentos = [];
                if (Storage::disk('public')->exists($customFolderPath)) {
                    $archivos = Storage::disk('public')->files($customFolderPath);
                    foreach ($archivos as $archivo) {
                        $nombreArchivo = pathinfo($archivo, PATHINFO_FILENAME);
                        $extension = pathinfo($archivo, PATHINFO_EXTENSION);
                        $rutaArchivo = Storage::url($archivo);

                        // Extraer referencia y nombre del archivo
                        $partes = explode('-', $nombreArchivo, 3);
                        $referencia = $partes[0] ?? '';
                        $nombre = $partes[1] ?? '';

                        $documentos[] = (object) [
                            'nombre' => $nombre,
                            'referencia' => $referencia,
                            'ruta' => $rutaArchivo,
                            'extension' => $extension
                        ];
                    }
                }

                return view('armonia.servicio_anexo_30.datos_servicio_anexo.documentacion_general', compact('requiredDocuments', 'documentos', 'id', 'servicio'));
            } else {
                return redirect()->route('armonia.servicio_anexo_30.datos_servicio_anexo.documentacion_general')->with('error', 'No se proporcionó un ID de estación.');
            }
        } catch (\Exception $e) {
            return redirect()->route('servicio_inspector_anexo_30.index')->with('error', 'Error al obtener la documentación: ' . $e->getMessage());
        }

        
    }

    public function storeDocumentosGenerales(Request $request){
       
        $data = $request->validate([
            'rutadoc_estacion' => 'required|file',
            'servicio_id' => 'required',
            'nomenclatura' => 'required',
            'nombre' => 'required',
            'referencia' => 'required',
            'id_documento'=>'required'
        ]);

        try {
            $documento = Documento_Servicio_Anexo::firstOrNew(['servicio_id' => $data['servicio_id']]);

            if ($request->hasFile('rutadoc_estacion')) {
                $archivoSubido = $request->file('rutadoc_estacion');
                $nombreArchivoPersonalizado = $data['referencia'] . '-' . $data['nombre'] . '-' . $data['id_documento']. '.' . $archivoSubido->getClientOriginalExtension();

                $nomenclatura = $data['nomenclatura'];
                $customFolderPath = "servicios_anexo30/{$nomenclatura}/documentacion/generales";

                if (!Storage::disk('public')->exists($customFolderPath)) {
                    Storage::disk('public')->makeDirectory($customFolderPath);
                }
                
                // Verificar si el archivo ya existe y eliminarlo
                $archivosExistentes = Storage::disk('public')->files($customFolderPath);
                $nombreSinReferenciaYId = "{$data['nombre']}";

                foreach ($archivosExistentes as $archivoExistente) {
                    // Extraer la parte del nombre del archivo existente
                    $partesArchivoExistente = explode('-', basename($archivoExistente, '.' . pathinfo($archivoExistente, PATHINFO_EXTENSION)));
                    if (count($partesArchivoExistente) > 1) {
                        $nombreExistente = $partesArchivoExistente[1];
                        if ($nombreExistente === $nombreSinReferenciaYId) {
                            Storage::disk('public')->delete($archivoExistente);
                        }
                    }
                }


                $rutaArchivo = $archivoSubido->storeAs("public/{$customFolderPath}", $nombreArchivoPersonalizado);

                $documento->rutadoc_estacion = "servicios_anexo30/{$nomenclatura}/documentacion";
            }

            $documento->servicio_id = $data['servicio_id'];
            $documento->usuario_id = Auth::id();
            $documento->save();

            return redirect()->route('documentacion_anexo_general', ['id' => $data['servicio_id']])->with('success', 'Documento guardado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('documentacion_anexo_general', ['id' => $data['servicio_id']])->with('error', 'Documento no guardado exitosamente.');
        }

    }

    //REQUSISITOS PARA LA APROBACIÓN DEL SISTEMA INFORMATICO ANEXOS 30 Y 31
    public function documentosSistemaInformatico(Request $request){
       
        try {
            if ($request->has('id')) {
                $id = $request->input('id');
                $servicio = ServicioAnexo::findOrFail($id);
                $nomenclatura = str_replace([' ', '.'], '_', $servicio->nomenclatura);
                $customFolderPath = "servicios_anexo30/{$nomenclatura}/documentacion/informatico";

                $requiredDocuments = [
                    ['descripcion' => 'Inventario de Activos tecnológicos relacionados con el control Volumétrico', 'codigo' => '', 'tipo' => 'Documental','id'=>1],
                    ['descripcion' => 'Manual de Usuario de control volumétrico, de preferencia si incluye apartado de cumplimiento anexos 30 y 31 RMF', 'codigo' => '', 'tipo' => 'Documental','id'=>2],
                    ['descripcion' => 'Información técnica de la base de datos utilizada en el control volumétrico', 'codigo' => '', 'tipo' => 'Documental','id'=>3],
                    ['descripcion' => 'Documentación técnica del programa informático utilizado como control volumétrico', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>4],                  
                    ['descripcion' => 'Evidencia de realizar pruebas de seguridad anual y evidencia del seguimiento a los hallazgos encontrados durante las pruebas', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>5],                  
                    ['descripcion' => 'Política y procedimientos de control de acceso al programa informático para el control volumétrico', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>6],                  
                    ['descripcion' => 'Procedimientos de restricción, control de asignación y uso de privilegios de acceso al programa informático', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>7],                  
                    ['descripcion' => 'Evidencia de depuración y revisión de usuarios cada 6 meses en el programa informático para el control volumétrico', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>8],                  
                    ['descripcion' => 'Procedimiento de control de cambios', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>9],                  
                    ['descripcion' => 'Contrato de Arrendamiento o pólizas de contratación del programa informático para el control volumétrico', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>10],                  
                    ['descripcion' => 'Políticas y procedimientos para la generación de respaldos de la información', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>11],                  
                    ['descripcion' => 'Organigrama, estructura y mapa de la red informática que interactúa con los sistemas de medición y los programas informáticos de control volumétrico', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>12],                  
                    ['descripcion' => 'Políticas y procedimientos para la gestión de incidentes de seguridad relacionados con el programa informático para llevar controles volumétricos', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>13],                  
                    ['descripcion' => 'Acuerdos de confidencialidad firmado con el personal de desarrollo e implementación del programa informático', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>14],                  
                    ['descripcion' => 'Pólizas y contratos de Control volumétrico', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>15],                  
                ];

                $documentos = [];
                if (Storage::disk('public')->exists($customFolderPath)) {
                    $archivos = Storage::disk('public')->files($customFolderPath);
                    foreach ($archivos as $archivo) {
                        $nombreArchivo = pathinfo($archivo, PATHINFO_FILENAME);
                        $extension = pathinfo($archivo, PATHINFO_EXTENSION);
                        $rutaArchivo = Storage::url($archivo);

                        // Extraer referencia y nombre del archivo
                        $partes = explode('-', $nombreArchivo, 3);
                        $referencia = $partes[0] ?? '';
                        $nombre = $partes[1] ?? '';

                        $documentos[] = (object) [
                            'nombre' => $nombre,
                            'referencia' => $referencia,
                            'ruta' => $rutaArchivo,
                            'extension' => $extension
                        ];
                    }
                }

                return view('armonia.servicio_anexo_30.datos_servicio_anexo.documentacion_sistemaInformatico', compact('requiredDocuments', 'documentos', 'id', 'servicio'));
            } else {
                return redirect()->route('armonia.servicio_anexo_30.datos_servicio_anexo.documentacion_sistemaInformatico')->with('error', 'No se proporcionó un ID de estación.');
            }
        } catch (\Exception $e) {
            return redirect()->route('servicio_inspector_anexo_30.index')->with('error', 'Error al obtener la documentación: ' . $e->getMessage());
        }

        
    }

    public function storeDocumentosSistemaInformatico(Request $request){
       
        $data = $request->validate([
            'rutadoc_estacion' => 'required|file',
            'servicio_id' => 'required',
            'nomenclatura' => 'required',
            'nombre' => 'required',
            'referencia' => 'required',
            'id_documento'=>'required'
        ]);

        try {
            $documento = Documento_Servicio_Anexo::firstOrNew(['servicio_id' => $data['servicio_id']]);

            if ($request->hasFile('rutadoc_estacion')) {
                $archivoSubido = $request->file('rutadoc_estacion');
                $nombreArchivoPersonalizado = $data['referencia'] . '-' . $data['nombre'] . '-' . $data['id_documento']. '.' . $archivoSubido->getClientOriginalExtension();

                $nomenclatura = $data['nomenclatura'];
                $customFolderPath = "servicios_anexo30/{$nomenclatura}/documentacion/informatico";

                if (!Storage::disk('public')->exists($customFolderPath)) {
                    Storage::disk('public')->makeDirectory($customFolderPath);
                }

                // Verificar si el archivo ya existe y eliminarlo
                $archivosExistentes = Storage::disk('public')->files($customFolderPath);
                $nombreSinReferenciaYId = "{$data['nombre']}";

                foreach ($archivosExistentes as $archivoExistente) {
                    // Extraer la parte del nombre del archivo existente
                    $partesArchivoExistente = explode('-', basename($archivoExistente, '.' . pathinfo($archivoExistente, PATHINFO_EXTENSION)));
                    if (count($partesArchivoExistente) > 1) {
                        $nombreExistente = $partesArchivoExistente[1];
                        if ($nombreExistente === $nombreSinReferenciaYId) {
                            Storage::disk('public')->delete($archivoExistente);
                        }
                    }
                }

                $rutaArchivo = $archivoSubido->storeAs("public/{$customFolderPath}", $nombreArchivoPersonalizado);

                $documento->rutadoc_estacion = "servicios_anexo30/{$nomenclatura}/documentacion";
            }

            $documento->servicio_id = $data['servicio_id'];
            $documento->usuario_id = Auth::id();
            $documento->save();

            return redirect()->route('documentacion_anexo_informaticos', ['id' => $data['servicio_id']])->with('success', 'Documento guardado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('documentacion_anexo_informaticos', ['id' => $data['servicio_id']])->with('error', 'Documento no guardado exitosamente.');
        }

    }

    //DURANTE LA INSPECCIÓN DE SOLICITARAN LAS SIGUIENTES EVIDENCIAS AL MOMENTO. 
    public function documentosInspeccion(Request $request){

        try {
            if ($request->has('id')) {
                $id = $request->input('id');
                $servicio = ServicioAnexo::findOrFail($id);
                $nomenclatura = str_replace([' ', '.'], '_', $servicio->nomenclatura);
                $customFolderPath = "servicios_anexo30/{$nomenclatura}/documentacion/inspeccion";

                $requiredDocuments = [
                    ['descripcion' => 'Una tirilla de inventario de la consola de monitoreo de tanques', 'codigo' => '', 'tipo' => 'Documental','id'=>1],
                    ['descripcion' => 'Impresión de la configuración de la consola de monitoreo de tanques', 'codigo' => '', 'tipo' => 'Documental','id'=>2],
                    ['descripcion' => 'La factura de una compra con su soporte (Remisión, Carta porte, Tira de Inicio y Fin de Incremento)', 'codigo' => '', 'tipo' => 'Documental','id'=>3],
                    ['descripcion' => 'La factura de una venta ', 'codigo' => '', 'tipo' => 'Documental y Fotos','id'=>4],                  
                ];

                $documentos = [];
                if (Storage::disk('public')->exists($customFolderPath)) {
                    $archivos = Storage::disk('public')->files($customFolderPath);
                    foreach ($archivos as $archivo) {
                        $nombreArchivo = pathinfo($archivo, PATHINFO_FILENAME);
                        $extension = pathinfo($archivo, PATHINFO_EXTENSION);
                        $rutaArchivo = Storage::url($archivo);

                        // Extraer referencia y nombre del archivo
                        $partes = explode('-', $nombreArchivo, 3);
                        $referencia = $partes[0] ?? '';
                        $nombre = $partes[1] ?? '';

                        $documentos[] = (object) [
                            'nombre' => $nombre,
                            'referencia' => $referencia,
                            'ruta' => $rutaArchivo,
                            'extension' => $extension
                        ];
                    }
                }

                return view('armonia.servicio_anexo_30.datos_servicio_anexo.documentacion_inspeccion', compact('requiredDocuments', 'documentos', 'id', 'servicio'));
            } else {
                return redirect()->route('armonia.servicio_anexo_30.datos_servicio_anexo.documentacion_inspeccion')->with('error', 'No se proporcionó un ID de estación.');
            }
        } catch (\Exception $e) {
            return redirect()->route('servicio_inspector_anexo_30.index')->with('error', 'Error al obtener la documentación: ' . $e->getMessage());
        }
    }

    public function storedocumentosInspeccion(Request $request){
        $data = $request->validate([
            'rutadoc_estacion' => 'required|file',
            'servicio_id' => 'required',
            'nomenclatura' => 'required',
            'nombre' => 'required',
            'referencia' => 'required',
            'id_documento'=>'required'
        ]);

        try {
            $documento = Documento_Servicio_Anexo::firstOrNew(['servicio_id' => $data['servicio_id']]);

            if ($request->hasFile('rutadoc_estacion')) {
                $archivoSubido = $request->file('rutadoc_estacion');
                $nombreArchivoPersonalizado = $data['referencia'] . '-' . $data['nombre'] . '-' . $data['id_documento']. '.' . $archivoSubido->getClientOriginalExtension();

                $nomenclatura = $data['nomenclatura'];
                $customFolderPath = "servicios_anexo30/{$nomenclatura}/documentacion/inspeccion";

                if (!Storage::disk('public')->exists($customFolderPath)) {
                    Storage::disk('public')->makeDirectory($customFolderPath);
                }

                // Verificar si el archivo ya existe y eliminarlo
                $archivosExistentes = Storage::disk('public')->files($customFolderPath);
                $nombreSinReferenciaYId = "{$data['nombre']}";

                foreach ($archivosExistentes as $archivoExistente) {
                    // Extraer la parte del nombre del archivo existente
                    $partesArchivoExistente = explode('-', basename($archivoExistente, '.' . pathinfo($archivoExistente, PATHINFO_EXTENSION)));
                    if (count($partesArchivoExistente) > 1) {
                        $nombreExistente = $partesArchivoExistente[1];
                        if ($nombreExistente === $nombreSinReferenciaYId) {
                            Storage::disk('public')->delete($archivoExistente);
                        }
                    }
                }

                $rutaArchivo = $archivoSubido->storeAs("public/{$customFolderPath}", $nombreArchivoPersonalizado);

                $documento->rutadoc_estacion = "servicios_anexo30/{$nomenclatura}/documentacion";
            }

            $documento->servicio_id = $data['servicio_id'];
            $documento->usuario_id = Auth::id();
            $documento->save();

            return redirect()->route('documentacion_anexo_inspeccion', ['id' => $data['servicio_id']])->with('success', 'Documento guardado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('documentacion_anexo_inspeccion', ['id' => $data['servicio_id']])->with('error', 'Documento no guardado exitosamente.');
        }
    }


}
