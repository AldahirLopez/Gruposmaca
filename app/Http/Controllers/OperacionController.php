<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\DictamenOp;
use Illuminate\Support\Facades\Storage;
use App\Models\ServicioOperacion;
use Illuminate\Support\Facades\Auth; // Importa la clase Auth
use App\Models\Estacion;
use App\Models\Expediente_Operacion;
use App\Models\Documento_Servicio_operacion;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\Cotizacion_Operacion;
use App\Models\Pago_Operacion;
use App\Models\Factura_Operacion;
use App\Models\Acta_Operacion;
//Este controlador se va utilizar para la parte del administrador donde aprueba los servicios de operacion y mantenimiento

use Barryvdh\DomPDF\Facade\Pdf;

class OperacionController extends Controller
{

    protected $connection = 'segunda_db';

    function __construct()
    {
       
        //EXPENDIENTE
        $this->middleware('permission:Generar-expediente-operacion', ['only' => ['ExpedienteInspectorOperacion','generarExpedientesOperacion']]);
        $this->middleware('permission:Descargar-documentos-expediente-operacion', ['only' => ['descargarWord']]);
        //DOCUMENTACION
        $this->middleware('permission:Generar-documentacion-operacion', ['only' => ['DocumentacionOperacion','storeDocumenctacionOperacion']]);
        $this->middleware('permission:Descargar-documentacion-operacion', ['only' => ['descargardocumentacion']]);       
        //COTIZACION
        $this->middleware('permission:Descargar-cotizacion-operacion', ['only' => ['descargarCotizacionAjax']]);
        $this->middleware('permission:Generar-cotizacion-operacion', ['only' => ['generarpdfcotizacion']]);
        //PAGO
        $this->middleware('permission:Ver-pagos', ['only' => ['pagos']]);
        $this->middleware('permission:Subir-pago-operacion', ['only' => ['storePago']]);
        $this->middleware('permission:Descargar-pago-operacion', ['only' => ['descargarPago']]);
        //FACTURA
        $this->middleware('permission:Subir-factura-operacion', ['only' => ['storeFactura']]);
        $this->middleware('permission:Descargar-factura-operacion', ['only' => ['descargarFactura']]);

        

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Pasar los dictámenes a la vista
        return view('armonia.operacion.index');
    }
    public function ExpedienteInspectorOperacion($slug)
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
        if ($usuario->hasAnyRole(['Administrador', 'Operacion y Mantenimiento'])) {
            // Si es administrador o auditor puede ver todo y editar todo 
            $servicio_id = $slug;
            $servicioAnexo = ServicioOperacion::find($servicio_id);

            // Obtener la estación relacionada con el servicio anexo
            $estacion = Estacion::whereHas('estacionServicioOperacionMantenimiento', function ($query) use ($servicio_id) {
                $query->where('servicio_operacion_id', $servicio_id);
            })->first();

            // Ruta de la carpeta donde se guardan los archivos generados
            $folderPath = "OperacionyMantenimiento/{$servicioAnexo->nomenclatura}/formatos_rellenados_operacion";
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
            return view('armonia.operacion.servicio_operacion.datos_servicio_operacion.expediente', compact('estacion', 'estados', 'servicio_id', 'servicioAnexo', 'existingFiles', 'estaciones'));
        } else {
            // Verificar si el usuario tiene acceso al servicio
            $servicio_id = $slug;
            $servicioAnexo = ServicioOperacion::find($servicio_id);
            $validar_servicio = ($servicioAnexo->usuario_id == $usuario->id);

            if ($validar_servicio) {
                // Obtener la estación relacionada con el servicio anexo
                $estacion = Estacion::whereHas('estacionServiciosOperacionMantenimiento', function ($query) use ($servicio_id) {
                    $query->where('servicio_operacion_id', $servicio_id);
                })->first();

                // Ruta de la carpeta donde se guardan los archivos generados
                $folderPath = "OperacionyMantenimiento/{$servicioAnexo->nomenclatura}/formatos_rellenados_operacion";
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

                return view('armonia.operacion.servicio_operacion.datos_servicio_operacion.expediente', compact('estacion', 'estados', 'servicio_id', 'servicioAnexo', 'existingFiles'));
            } else {
                return redirect()->route('servicio_anexo_30.datos_servicio_anexo.index')->with('error', 'Servicio no válido');
            }
        }
    }



    public function generateWord(Request $request)
    {

        try {
            // Obtener el ID de la estación desde la solicitud
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
            $customFolderPath = "OperacionyMantenimiento/{$data['nomenclatura']}";
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
                $templateProcessor = new TemplateProcessor(storage_path("app/templates/OperacionyMantenimiento/{$templatePath}"));

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

            $servicio = ServicioOperacion::firstOrNew(['id' => $data['id_servicio']]);
            $servicio->date_recepcion_at = $data['fecha_recepcion'];
            $servicio->date_inspeccion_at = $data['fecha_inspeccion'];
            $servicio->save();

            $expediente = Expediente_Operacion::firstOrNew(['operacion_mantenimiento_id' => $data['id_servicio']]);
            $expediente->rutadoc_expediente = $subFolderPath;
            $expediente->operacion_mantenimiento_id = $data['id_servicio'];
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
            return redirect()->route('expediente.operacion', ['slug' => $data['id_servicio']])
                ->with('generatedFiles', $generatedFiles);
        } catch (\Exception $e) {
            // Capturar y registrar cualquier excepción ocurrida
            \Log::error("Error al generar documentos: " . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al procesar la solicitud. Por favor, intenta de nuevo más tarde.'], 500);
        }
    }
    public function generarExpedientesOperacion(Request $request)
    {

        try {
            // Obtener el ID de la estación desde la solicitud
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
                'cantidad' => 'required',
                'observaciones' => 'nullable',
                'iva' => 'required',
                'fecha_inspeccion' => 'required|date',
                'images.*' => 'required|image|mimes:jpeg,png,jpg,gif',
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
            $data['total'] = $data['cantidad'] * (1 + $data['iva']);

            $data['iva'] = $data['cantidad'] * $data['iva'];

            $data['total_mitad'] = $data['total'] * 0.50;

            $data['total_restante'] = $data['total'] - $data['total_mitad'];




            // Convertir las fechas al formato deseado
            $fechaInspeccion = Carbon::createFromFormat('Y-m-d', $data['fecha_inspeccion'])->format('d-m-Y');
            $fechaRecepcion = Carbon::createFromFormat('Y-m-d', $data['fecha_recepcion'])->format('d-m-Y');
            $fechaInspeccionAumentada = Carbon::createFromFormat('Y-m-d', $data['fecha_inspeccion'])->addYear()->format('d-m-Y');

            // Cargar las plantillas de Word
            $templatePaths = [             
                'CONTRATO.docx',
                'DETEC. R.I.docx',
                'PLAN DE INSPECCIÓN OPERACIÓN Y MANTENIMIENTO.docx',
                'ORDEN DE TRABAJO.docx',
                'REPORTE FOTOGRAFICO.docx',

            ];

            // Definir la carpeta de destino
            $customFolderPath = "OperacionyMantenimiento/{$data['nomenclatura']}";
            $subFolderPath = "{$customFolderPath}/expediente";
            $carpetaImages = "{$subFolderPath}/imagenes";
            // Crear la carpeta personalizada si no existe
            if (!Storage::disk('public')->exists($customFolderPath)) {
                Storage::disk('public')->makeDirectory($customFolderPath);
            }

            // Verificar y crear la subcarpeta si no existe
            if (!Storage::disk('public')->exists($subFolderPath)) {
                Storage::disk('public')->makeDirectory($subFolderPath);
            }

            //Creamos la carpteta donde iran las imagenes del reporte fotografico
            if (!Storage::disk('public')->exists($carpetaImages)) {
                Storage::disk('public')->makeDirectory($carpetaImages);
            }

            //Obtener las imagenes
            $imageNumber = 1;

            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                // Generar el nombre de la imagen

                $imageName = 'img_' . $imageNumber . '.' . $image->extension();


                // Mover la imagen a la carpeta de destino, reemplazando si existe
                $image->storeAs($carpetaImages, $imageName, 'public');


                // Obtener la ruta completa de la imagen
                $imagePath = Storage::disk('public')->path("$carpetaImages/$imageName") ?? null;

                // Almacenar la ruta de la imagen en el array
                $imagePaths[] = [
                    'name' => 'img_' . $imageNumber,
                    'path' => $imagePath,
                ];
                $imageNumber++;
            }


            // Reemplazar marcadores en todas las plantillas
            foreach ($templatePaths as $templatePath) {
                $templateProcessor = new TemplateProcessor(storage_path("app/templates/OperacionyMantenimiento/{$templatePath}"));

                if ($templatePath == "REPORTE FOTOGRAFICO.docx") {

                    for ($i = 0; $i < count($imagePaths); $i++) {
                        $templateProcessor->setImageValue($imagePaths[$i]['name'], array('path' => $imagePaths[$i]['path'], 'width' => 310, 'height' => 285, 'ratio' => false));
                    }
                }
                $data['images'] = null;
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

            $servicio = ServicioOperacion::firstOrNew(['id' => $data['id_servicio']]);
            $servicio->date_recepcion_at = $data['fecha_recepcion'];
            $servicio->date_inspeccion_at = $data['fecha_inspeccion'];
            $servicio->save();

            $expediente = Expediente_Operacion::firstOrNew(['operacion_mantenimiento_id' => $data['id_servicio']]);
            $expediente->rutadoc_expediente = $subFolderPath;
            $expediente->operacion_mantenimiento_id = $data['id_servicio'];
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
            return redirect()->route('expediente.operacion', ['slug' => $data['id_servicio']])
                ->with('generatedFiles', $generatedFiles);
        } catch (\Exception $e) {
            // Capturar y registrar cualquier excepción ocurrida
            \Log::error("Error al generar documentos: " . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al procesar la solicitud. Por favor, intenta de nuevo más tarde.'], 500);
        }
    }


    public function listGeneratedFiles($nomenclatura)
    {
        $folderPath = "OperacionyMantenimiento/{$nomenclatura}/expediente/";
        $files = Storage::disk('public')->files($folderPath);

        $generatedFiles = array_map(function ($filePath) {
            return [
                'name' => basename($filePath),
                'url' => Storage::url($filePath),
            ];
        }, $files);

        return response()->json(['generatedFiles' => $generatedFiles]);
    }

    public function descargarWord(Request $request, $archivo, $nomenclatura)
    {
        // Ejemplo de construcción de la ruta del archivo
        $nomenclatura = strtoupper($nomenclatura); // Obtener la nomenclatura desde la ruta

        // Construir la ruta del archivo
        $rutaArchivo = storage_path("app/public/OperacionyMantenimiento/{$nomenclatura}/expediente/{$archivo}");

        // Verificar si el archivo existe antes de proceder
        if (file_exists($rutaArchivo)) {
            // Devolver el archivo para descargar
            return response()->download($rutaArchivo);
        } else {
            // Manejar el caso en que el archivo no exista
            abort(404, "El archivo no existe en la ruta especificada.");
        }
    }

    public function validarDatosExpediente($id)
    {
        try {
            // Cambiar la conexión a la base de datos 'segunda_db' y verificar la existencia de expedientes
            $existeExpediente = DB::connection('segunda_db')
                ->table('expediente_servicio_operacion')
                ->where('operacion_mantenimiento_id', $id)
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


    public function apro_servicio_operacion_mantenimiento()
    {
        // Obtener el usuario autenticado
        $usuario = Auth::user();

        // Verificar si el usuario es administrador
        if (auth()->check() && $usuario->hasAnyRole(['Administrador', 'Auditor'])) {
            // Si es administrador, obtener todos los dictámenes
            $servicios = ServicioOperacion::all();
        } else {
            // Si no es administrador, obtener solo los dictámenes del usuario autenticado
            $servicios = ServicioOperacion::where('usuario_id', $usuario->id)->get();
        }

        // Pasar los dictámenes a la vista
        return view('armonia.operacion.servicio_operacion.aprobacion_operacion.index', compact('servicios'));
    }



    public function apro($id)
    {
        try {
            // Buscar el servicio por su ID
            $servicio = ServicioOperacion::findOrFail($id);

            // Establecer pending_apro_servicio como true
            $servicio->pending_apro_servicio = true;
            $servicio->save();

            // Redireccionar con un mensaje de éxito
            return redirect()->route('apro.operacion')->with('success', 'Servicio aprobado correctamente.');
        } catch (ModelNotFoundException $e) {
            // Manejar la excepción si el servicio no se encuentra
            return redirect()->route('apro.operacion') > with('error', 'Servicio no encontrado.');
        }
    }





    //Metodo para filtrar por estado ,año y usuario
    public function obtenerServicios(Request $request)
    {
        $usuarioSeleccionado = $request->input('usuario_id');
        $estadoSeleccionado = $request->input('estado');
        $yearSeleccionado = $request->input('year');


        // Obtener el usuario autenticado
        $usuario = Auth::user();

        // Obtener el rol "Verificador Anexo 30"
        $rol = Role::on('mysql')->where('name', 'Operacion y Mantenimiento')->first();

        if (!$rol) {
            throw new \Exception('El rol "Operación y Mantenimiento" no existe.');
        }

        // Obtener los IDs de los usuarios con el rol específico
        $usuariosConRol = $rol->users()->pluck('id');

        if ($usuariosConRol->isEmpty()) {
            throw new \Exception('No hay usuarios con el rol "Operacion y Mantenimiento".');
        }

        // Obtener los usuarios correspondientes a esos IDs
        $usuarios = User::on('mysql')->whereIn('id', $usuariosConRol)->get();

        if ($usuarioSeleccionado === "todos") {
            $servicios = ServicioOperacion::all();
        }
        $usuario = User::find($usuarioSeleccionado);

        $servicios = ServicioOperacion::query()
            ->join('estacion_servicio_operacion_mantenimiento', 'operacion_mantenimiento.id', '=', 'estacion_servicio_operacion_mantenimiento.servicio_operacion_id')
            ->join('estacion', 'estacion.id', '=', 'estacion_servicio_operacion_mantenimiento.estacion_id')
            ->select('operacion_mantenimiento.*')
            ->when($usuarioSeleccionado != 'todos', function ($query) use ($usuarioSeleccionado) {
                return $query->where('operacion_mantenimiento.usuario_id', $usuarioSeleccionado);
            })
            ->when($yearSeleccionado, function ($query) use ($yearSeleccionado) {
                return $query->whereYear('operacion_mantenimiento.created_at', $yearSeleccionado);
            })
            ->when($estadoSeleccionado, function ($query) use ($estadoSeleccionado) {
                return $query->where('estacion.estado_republica_estacion', $estadoSeleccionado);
            })
            ->get();



        // Pasar los datos a la vista
        return redirect()->route('servicio_operacion.index')->with(['servicios' => $servicios, 'año' => $yearSeleccionado, 'estado' => $estadoSeleccionado, 'usuario' => $usuario]);
    }



    public function DocumentacionOperacion(Request $request)
    {
        try {
            if ($request->has('id')) {
                $id = $request->input('id');
                $servicio = ServicioOperacion::findOrFail($id);
                $nomenclatura = str_replace([' ', '.'], '_', $servicio->nomenclatura);
                $customFolderPath = "OperacionyMantenimiento/{$nomenclatura}/documentacion";

                $requiredDocuments = [
                    'ANALISIS DE RIESGO DEL SECTOR HIDROCARBUROS',
                    'PRUEBAS DE HERMETICIDAD',
                    'CARTA RESPONSIVA Y/O FACTURA DEL MANTENIMIENTO A EXTINTORES',
                    'DICTAMEN DE INSTALACIONES ELECTRICAS',
                    'ESTUDIO DE TIERRAS FISICAS',
                    'CERTIFICADO DE LIMPIEZA ECOLOGICA',
                    'PERMISO DE LA CRE',
                    'TIRILLA DEL REPORTE DE INVENTARIOS',
                    'TIRILLA DE LAS PRUEBAS DE SENSORES',
                    'IDENTIFICACION OFICIAL DE LA PERSONA QUE ATENDIO LA INSPECCION Y TESTIGOS'
                ];

                $documentos = [];
                if (Storage::disk('public')->exists($customFolderPath)) {
                    $archivos = Storage::disk('public')->files($customFolderPath);
                    foreach ($archivos as $archivo) {
                        $nombreArchivo = pathinfo($archivo, PATHINFO_FILENAME);
                        $rutaArchivo = Storage::url($archivo);
                        $documentos[] = (object) [
                            'nombre' => $nombreArchivo,
                            'ruta' => $rutaArchivo
                        ];
                    }
                }

                return view('armonia.operacion.servicio_operacion.datos_servicio_operacion.documentos', compact('requiredDocuments', 'documentos', 'id', 'servicio'));
            } else {
                return redirect()->route('armonia.operacion.servicio_operacion.datos_servicio_operacion.documentos')->with('error', 'No se proporcionó un ID de estación.');
            }
        } catch (\Exception $e) {
            return redirect()->route('servicio_inspector_anexo_30.index')->with('error', 'Error al obtener la documentación: ' . $e->getMessage());
        }
    }

    public function storeDocumenctacionOperacion(Request $request)
    {

        $data = $request->validate([
            'rutadoc_estacion' => 'required|file',
            'servicio_id' => 'required',
            'nomenclatura' => 'required',
            'nombre' => 'required',
        ]);

        try {
            $documento = Documento_Servicio_operacion::firstOrNew(['servicio_id' => $data['servicio_id']]);


            if ($request->hasFile('rutadoc_estacion')) {
                $archivoSubido = $request->file('rutadoc_estacion');
                $nombreArchivoPersonalizado = $data['nombre'] . '.' . $archivoSubido->getClientOriginalExtension();

                $nomenclatura = $data['nomenclatura'];
                $customFolderPath = "OperacionyMantenimiento/{$nomenclatura}/documentacion";

                if (!Storage::disk('public')->exists($customFolderPath)) {
                    Storage::disk('public')->makeDirectory($customFolderPath);
                }

                $rutaArchivo = $archivoSubido->storeAs("public/{$customFolderPath}", $nombreArchivoPersonalizado);

                $documento->rutadoc_estacion = $rutaArchivo;
            }

            $documento->servicio_id = $data['servicio_id'];
            $documento->usuario_id = Auth::id();
            $documento->save();

            return redirect()->route('documentacion_operacion', ['id' => $data['servicio_id']])->with('success', 'Documento guardado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('documentacion_operacion', ['id' => $data['servicio_id']])->with('error', 'Documento no guardado exitosamente.');
        }
    }

    public function descargardocumentacion(Request $request, $documento)
    {



        $nomenclatura = strtoupper($request->input('nomenclatura')); // Obtener la nomenclatura desde la ruta

        // Construir la ruta del archivo
        $rutaArchivo = storage_path("app/public/OperacionyMantenimiento/{$nomenclatura}/documentacion/{$documento}");

        // Verificar si el archivo existe antes de proceder
        if (file_exists($rutaArchivo)) {
            // Devolver el archivo para descargar
            return response()->download($rutaArchivo);
        } else {
            // Manejar el caso en que el archivo no exista
            abort(404, "El archivo no existe en la ruta especificada.");
        }
    }


    public function generarpdfcotizacion(Request $request)
    {
        app()->setLocale('es');

        $id_servicio = $request->input('id_servicio');
        $nomenclatura = $request->input('nomenclatura');
        $nombre_estacion = strtoupper($request->input('razon_social'));
        $direccion_estacion = strtoupper($request->input('direccion'));
        $costo = $request->input('costo');

        // Calcular el 16% de IVA 
        $iva = $costo * 0.16;

        //Calcular el total
        $total = $costo + $iva;


        $fecha_actual = Carbon::now()->formatLocalized('%A %d de %B de %Y');


        $folderPath = "OperacionyMantenimiento/{$nomenclatura}";
        $subFolderPath = "{$folderPath}/cotizacion";


        // Verificar y crear la carpeta principal si no existe
        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        // Verificar y crear la subcarpeta dentro de la carpeta principal si no existe
        if (!Storage::disk('public')->exists($subFolderPath)) {
            Storage::disk('public')->makeDirectory($subFolderPath);
        }


        // Definir la ruta completa del PDF
        $pdfPath = "{$subFolderPath}/Cotizacion_{$nomenclatura}.pdf";


        // Pasar los datos al PDF y renderizarlo, incluyendo la fecha actual
        $html = view('armonia.operacion.cotizacion.cotizacion', compact('nombre_estacion', 'direccion_estacion', 'costo', 'iva', 'fecha_actual'))->render();
        $pdf = PDF::loadHTML($html);

        Storage::disk('public')->put($pdfPath, $pdf->output());

        $pdfUrl = Storage::url($pdfPath);


        $cotizacion = Cotizacion_Operacion::where('servicio_id', $id_servicio)->first();

        if ($cotizacion) {
            // Si ya existe, actualiza el registro existente
            $cotizacion->rutadoc_cotizacion = $pdfUrl;
            $cotizacion->save();
        } else {
            // Si no existe, crea un nuevo registro
            $cotizacion = new Cotizacion_Operacion();
            $cotizacion->rutadoc_cotizacion = $pdfUrl;
            $cotizacion->servicio_id = $id_servicio;
            $cotizacion->estado_cotizacion = true;
            // Asigna otros campos si es necesario
            $cotizacion->save();
        }


        return response()->json(['pdf_url' => $pdfUrl]);
    }



    public function descargarCotizacionAjax(Request $request)
    {
        // Obtener la ruta del documento desde la solicitud GET
        $rutaDocumento = $request->query('rutaDocumento');

        // Construir la ruta completa del archivo de cotización
        $rutaCompleta = public_path($rutaDocumento);

        // Verificar si el archivo existe
        if (!file_exists($rutaCompleta)) {
            abort(404, 'El archivo solicitado no existe.');
        }

        // Devolver el archivo como una respuesta binaria (blob)
        return response()->file($rutaCompleta);
    }


    public function storePago(Request $request)
    {
        $data = $request->validate([
            'rutadoc' => 'required|file|mimes:pdf',
            'servicio_id' => 'required',
            'nomenclatura' => 'required',
        ]);

        try {
            $pago = Pago_Operacion::firstOrNew(['servicio_id' => $data['servicio_id']]);

            if ($request->hasFile('rutadoc')) {
                $archivoSubido = $request->file('rutadoc');

                $nombreArchivoPersonalizado = "Pago_" . $data['nomenclatura'] . '.' . $archivoSubido->getClientOriginalExtension();

                $nomenclatura = $data['nomenclatura'];

                // Definir la carpeta principal y la subcarpeta donde se guardarán los PDFs
                $folderPath = "OperacionyMantenimiento/{$nomenclatura}";
                $subFolderPath = "{$folderPath}/pago";

                // Verificar y crear la carpeta principal si no existe
                if (!Storage::disk('public')->exists($folderPath)) {
                    Storage::disk('public')->makeDirectory($folderPath);
                }

                // Verificar y crear la subcarpeta dentro de la carpeta principal si no existe
                if (!Storage::disk('public')->exists($subFolderPath)) {
                    Storage::disk('public')->makeDirectory($subFolderPath);
                }

                // Guardar el PDF en el almacenamiento de Laravel con un nombre personalizado
                $rutaArchivo = $archivoSubido->storeAs($subFolderPath, $nombreArchivoPersonalizado, 'public');

                // Obtener la URL pública del PDF
                $pdfUrl = Storage::url($rutaArchivo);

                $pago->rutadoc_pago = $pdfUrl;
            }

            $pago->servicio_id = $data['servicio_id'];
            $pago->estado_facturado = false;
            $pago->save();

            return redirect()->route('servicio_operacion.index', ['id' => $data['servicio_id']])->with('success', 'Pago guardado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('servicio_operacion.index', ['id' => $data['servicio_id']])->with('error', 'Pago no guardado exitosamente.');
        }
    }

    public function descargarPago(Request $request)
    {

        // Obtener la ruta del documento desde la solicitud GET
        $rutaDocumento = $request->query('rutaDocumento');

        // Construir la ruta completa del archivo de cotización
        $rutaCompleta = public_path($rutaDocumento);

        // Verificar si el archivo existe
        if (!file_exists($rutaCompleta)) {
            abort(404, 'El archivo solicitado no existe.');
        }

        // Devolver el archivo como una respuesta binaria (blob)
        return response()->file($rutaCompleta);
    }


    public function pagos()
    {

        $pagos = Pago_Operacion::all();

        return view('armonia.operacion.pagos.index', compact('pagos'));
    }



    public function storeFactura(Request $request)
    {
        $data = $request->validate([
            'rutadoc' => 'required|file|mimes:pdf',
            'servicio_id' => 'required',
            'nomenclatura' => 'required',
        ]);

        try {
            $pago = Pago_Operacion::where('servicio_id', $data['servicio_id'])->first();

            if (!$pago) {
                return redirect()->route('pagos.index', ['id' => $data['servicio_id']])->with('error', 'Pago no encontrado.');
            }

            $factura = Factura_Operacion::firstOrNew(['id_pago' => $pago->id]);

            if ($request->hasFile('rutadoc')) {
                $archivoSubido = $request->file('rutadoc');

                $nombreArchivoPersonalizado = "Factura_" . $data['nomenclatura'] . '.' . $archivoSubido->getClientOriginalExtension();

                $nomenclatura = $data['nomenclatura'];

                // Definir la carpeta principal y la subcarpeta donde se guardarán los PDFs
                $folderPath = "OperacionyMantenimiento/{$nomenclatura}";
                $subFolderPath = "{$folderPath}/facturas";

                // Verificar y crear la carpeta principal si no existe
                if (!Storage::disk('public')->exists($folderPath)) {
                    Storage::disk('public')->makeDirectory($folderPath);
                }

                // Verificar y crear la subcarpeta dentro de la carpeta principal si no existe
                if (!Storage::disk('public')->exists($subFolderPath)) {
                    Storage::disk('public')->makeDirectory($subFolderPath);
                }

                // Guardar el PDF en el almacenamiento de Laravel con un nombre personalizado
                $rutaArchivo = $archivoSubido->storeAs($subFolderPath, $nombreArchivoPersonalizado, 'public');

                // Obtener la URL pública del PDF
                $pdfUrl = Storage::url($rutaArchivo);

                $factura->rutadoc_factura = $pdfUrl;
            }

            $pago->estado_facturado = true;
            $pago->save();

            $factura->id_pago = $pago->id;
            $factura->estado_factura = true;
            $factura->save();

            return redirect()->route('pagos.index', ['id' => $data['servicio_id']])->with('success', 'Factura guardada exitosamente.');
        } catch (\Exception $e) {
            \Log::error('Error guardando la factura: ' . $e->getMessage());
            return redirect()->route('pagos.index', ['id' => $data['servicio_id']])->with('error', 'Factura no guardada exitosamente.');
        }
    }

    public function descargarFactura(Request $request)
    {

        // Obtener la ruta del documento desde la solicitud GET
        $rutaDocumento = $request->query('rutaDocumento');

        // Construir la ruta completa del archivo de cotización
        $rutaCompleta = public_path($rutaDocumento);

        // Verificar si el archivo existe
        if (!file_exists($rutaCompleta)) {
            abort(404, 'El archivo solicitado no existe.');
        }

        // Devolver el archivo como una respuesta binaria (blob)
        return response()->file($rutaCompleta);
    }


    public function generarComprobanteTraslado(Request $request){
       
       try {
        
            $rules = [
                    'nomenclatura' => 'required',
                    'idestacion' => 'required',
                    'id_servicio' => 'required',
                    'id_usuario' => 'required',
                    'fecha_emision1' => 'required|date',  
                    'fecha_inspeccion' => 'required|date', 
                    
                    'origen1'=>'required',
                    'destino_1'=>'required',
                    'transporte1'=>'required',
                    'comprobante1'=>'required',
                    'concepto1'=>'required',

                    'origen2'=>'required',
                    'destino_2'=>'required',
                    'transporte2'=>'required',
                    'comprobante2'=>'required',
                    'concepto2'=>'required',
                    'fecha_emision2'=>'required|date', 


            ];

          
           
            $data = $request->validate($rules);
           


            $idEstacion = $request->input('idestacion');
            // Buscar la estación por su ID y obtener los datos necesarios
            $estacion = Estacion::findOrFail($idEstacion);

            $data['domicilio_estacion'] = $estacion->domicilio_estacion_servicio;
            $data['razonsocial'] = $estacion->razon_social;
        
            $templatePaths = [             
                'COMPROBANTE DE TRASLADO.docx',
            ];
            // Definir la carpeta de destino
            $customFolderPath = "OperacionyMantenimiento/{$data['nomenclatura']}";
            $subFolderPath = "{$customFolderPath}/expediente";

            // Crear la carpeta personalizada si no existe
            if (!Storage::disk('public')->exists($customFolderPath)) {
                Storage::disk('public')->makeDirectory($customFolderPath);
            }

            // Verificar y crear la subcarpeta si no existe
            if (!Storage::disk('public')->exists($subFolderPath)) {
                Storage::disk('public')->makeDirectory($subFolderPath);
            }
            $fechaInspeccion = Carbon::createFromFormat('Y-m-d', $data['fecha_inspeccion'])->format('d-m-Y');
            $fechaEmision1=Carbon::createFromFormat('Y-m-d', $data['fecha_emision1'])->format('d-m-Y');
            $fechaEmision2=Carbon::createFromFormat('Y-m-d', $data['fecha_emision2'])->format('d-m-Y');


            foreach ($templatePaths as $templatePath) {
                $templateProcessor = new TemplateProcessor(storage_path("app/templates/OperacionyMantenimiento/{$templatePath}"));

                // Reemplazar todos los marcadores con los datos del formulario
                foreach ($data as $key => $value) {
                    $templateProcessor->setValue($key, $value);
                    // Reemplazar fechas formateadas específicas
                    $templateProcessor->setValue('fecha_inspeccion', $fechaInspeccion);
                    $templateProcessor->setValue('fecha_emision1', $fechaEmision1);
                    $templateProcessor->setValue('fecha_emision2', $fechaEmision2);
                   
                    switch ($data['transporte1']) {
                        case 'avion':
                            $templateProcessor->setValue('avion1', 'X');
                            $templateProcessor->setValue('autobus1', ' ');
                            $templateProcessor->setValue('taxi1', ' ');
                            $templateProcessor->setValue('oficial1', ' ');
                            $templateProcessor->setValue('otro1', ' ');
                            $templateProcessor->setValue('otro_text1', ' ');
                            break;
                        case 'autobus':
                            $templateProcessor->setValue('avion1', ' ');
                            $templateProcessor->setValue('autobus1', 'X');
                            $templateProcessor->setValue('taxi1', ' ');
                            $templateProcessor->setValue('oficial1', ' ');
                            $templateProcessor->setValue('otro1', ' ');
                            $templateProcessor->setValue('otro_text1', ' ');
                            break;
                        case 'taxi':
                            $templateProcessor->setValue('avion1', ' ');
                            $templateProcessor->setValue('autobus1', ' ');
                            $templateProcessor->setValue('taxi1', 'X');
                            $templateProcessor->setValue('oficial1', ' ');
                            $templateProcessor->setValue('otro1', ' ');
                            $templateProcessor->setValue('otro_text1', ' ');
                            break;
                        case 'oficial':
                            $templateProcessor->setValue('avion1', ' ');
                            $templateProcessor->setValue('autobus1', ' ');
                            $templateProcessor->setValue('taxi1', ' ');
                            $templateProcessor->setValue('oficial1', 'X');
                            $templateProcessor->setValue('otro1', ' ');
                            $templateProcessor->setValue('otro_text1', ' ');
                            break;
                        case 'otro':
                            $templateProcessor->setValue('avion1', ' ');
                            $templateProcessor->setValue('autobus1', ' ');
                            $templateProcessor->setValue('taxi1', ' ');
                            $templateProcessor->setValue('oficial1', ' ');
                            $templateProcessor->setValue('otro1', 'X');
                            $templateProcessor->setValue('otro_text1', $request->input('otro_transporte_text1'));
                            break;
                            
                        default:
                            // Manejar cualquier otro caso aquí si es necesario
                            break;
                    }


                    switch ($data['comprobante1']) {
                        case 'factura':
                            $templateProcessor->setValue('factura1', 'X');
                            $templateProcessor->setValue('boleto1', ' ');
                            $templateProcessor->setValue('otro2', ' ');
                            $templateProcessor->setValue('otro_text2', ' ');
                            break;
                        case 'boleto':
                            $templateProcessor->setValue('factura1', ' ');
                            $templateProcessor->setValue('boleto1', 'X');
                            $templateProcessor->setValue('otro2', ' ');
                            $templateProcessor->setValue('otro_text2', ' ');
                            break;
                    
                        case 'otro':
                            $templateProcessor->setValue('factura1', ' ');
                            $templateProcessor->setValue('boleto1', ' ');
                            $templateProcessor->setValue('otro2', 'X');
                            $templateProcessor->setValue('otro_text2', $request->input('otro_comprobante_text1'));
                            break;
           
                            
                        default:
                            // Manejar cualquier otro caso aquí si es necesario
                            break;
                    }


                    switch ($data['concepto1']) {
                        case 'pasaje':
                            $templateProcessor->setValue('pasaje1', 'X');
                            $templateProcessor->setValue('caseta1', ' ');
                            $templateProcessor->setValue('combustible1', ' ');
                            $templateProcessor->setValue('otro3', ' ');
                            $templateProcessor->setValue('otro_text3', ' ');
                            break;
                        case 'caseta':
                            $templateProcessor->setValue('pasaje1', ' ');
                            $templateProcessor->setValue('caseta1', 'X');
                            $templateProcessor->setValue('combustible1', ' ');
                            $templateProcessor->setValue('otro3', ' ');
                            $templateProcessor->setValue('otro_text3', ' ');
                            break;

                        case 'combustible':
                            $templateProcessor->setValue('pasaje1', ' ');
                            $templateProcessor->setValue('caseta1', ' ');
                            $templateProcessor->setValue('combustible1', 'X');
                            $templateProcessor->setValue('otro3', ' ');
                            $templateProcessor->setValue('otro_text3', ' ');
                            break;      

                    
                        case 'otro':
                            $templateProcessor->setValue('pasaje1', ' ');
                            $templateProcessor->setValue('caseta1', ' ');
                            $templateProcessor->setValue('combustible1', ' ');
                            $templateProcessor->setValue('otro3', 'X');
                            $templateProcessor->setValue('otro_text3', $request->input('otro_concepto_text1'));
                            break;
           
                            
                        default:
                            // Manejar cualquier otro caso aquí si es necesario
                            break;
                    }


                    switch ($data['transporte2']) {
                        case 'avion':
                            $templateProcessor->setValue('avion2', 'X');
                            $templateProcessor->setValue('autobus2', ' ');
                            $templateProcessor->setValue('taxi2', ' ');
                            $templateProcessor->setValue('oficial2', ' ');
                            $templateProcessor->setValue('otro4', ' ');
                            $templateProcessor->setValue('otro_text4', ' ');
                            break;
                        case 'autobus':
                            $templateProcessor->setValue('avion2', ' ');
                            $templateProcessor->setValue('autobus2', 'X');
                            $templateProcessor->setValue('taxi2', ' ');
                            $templateProcessor->setValue('oficial2', ' ');
                            $templateProcessor->setValue('otro4', ' ');
                            $templateProcessor->setValue('otro_text4', ' ');
                            break;
                        case 'taxi':
                            $templateProcessor->setValue('avion2', ' ');
                            $templateProcessor->setValue('autobus2', ' ');
                            $templateProcessor->setValue('taxi2', 'X');
                            $templateProcessor->setValue('oficial2', ' ');
                            $templateProcessor->setValue('otro4', ' ');
                            $templateProcessor->setValue('otro_text4', ' ');
                            break;
                        case 'oficial':
                            $templateProcessor->setValue('avion2', ' ');
                            $templateProcessor->setValue('autobus2', ' ');
                            $templateProcessor->setValue('taxi2', ' ');
                            $templateProcessor->setValue('oficial2', 'X');
                            $templateProcessor->setValue('otro4', ' ');
                            $templateProcessor->setValue('otro_text4', ' ');
                            break;
                        case 'otro':
                            $templateProcessor->setValue('avion2', ' ');
                            $templateProcessor->setValue('autobus2', ' ');
                            $templateProcessor->setValue('taxi2', ' ');
                            $templateProcessor->setValue('oficial2', ' ');
                            $templateProcessor->setValue('otro4', 'X');
                            $templateProcessor->setValue('otro_text4', $request->input('otro_trasnporte2_text'));
                            break;
                            
                        default:
                            // Manejar cualquier otro caso aquí si es necesario
                            break;
                    }

                    switch ($data['comprobante2']) {
                        case 'factura':
                            $templateProcessor->setValue('factura2', 'X');
                            $templateProcessor->setValue('boleto2', ' ');
                            $templateProcessor->setValue('otro5', ' ');
                            $templateProcessor->setValue('otro_text5', ' ');
                            break;
                        case 'boleto':
                            $templateProcessor->setValue('factura2', ' ');
                            $templateProcessor->setValue('boleto2', 'X');
                            $templateProcessor->setValue('otro5', ' ');
                            $templateProcessor->setValue('otro_text5', ' ');
                            break;
                    
                        case 'otro':
                            $templateProcessor->setValue('factura2', ' ');
                            $templateProcessor->setValue('boleto2', ' ');
                            $templateProcessor->setValue('otro5', 'X');
                            $templateProcessor->setValue('otro_text5', $request->input('otro_comprobante2_text'));
                            break;
           
                            
                        default:
                            // Manejar cualquier otro caso aquí si es necesario
                            break;
                    }


                    switch ($data['concepto2']) {
                        case 'pasaje':
                            $templateProcessor->setValue('pasaje2', 'X');
                            $templateProcessor->setValue('caseta2', ' ');
                            $templateProcessor->setValue('combustible2', ' ');
                            $templateProcessor->setValue('otro6', ' ');
                            $templateProcessor->setValue('otro_text6', ' ');
                            break;
                        case 'caseta':
                            $templateProcessor->setValue('pasaje2', ' ');
                            $templateProcessor->setValue('caseta2', 'X');
                            $templateProcessor->setValue('combustible2', ' ');
                            $templateProcessor->setValue('otro6', ' ');
                            $templateProcessor->setValue('otro_text6', ' ');
                            break;

                        case 'combustible':
                            $templateProcessor->setValue('pasaje2', ' ');
                            $templateProcessor->setValue('caseta2', ' ');
                            $templateProcessor->setValue('combustible2', 'X');
                            $templateProcessor->setValue('otro6', ' ');
                            $templateProcessor->setValue('otro_text6', ' ');
                            break;      

                    
                        case 'otro':
                            $templateProcessor->setValue('pasaje2', ' ');
                            $templateProcessor->setValue('caseta2', ' ');
                            $templateProcessor->setValue('combustible2', ' ');
                            $templateProcessor->setValue('otro6', 'X');
                            $templateProcessor->setValue('otro_text6', $request->input('otro_concepto2_text'));
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

            $expediente = Expediente_Operacion::firstOrNew(['operacion_mantenimiento_id' => $data['id_servicio']]);
            $expediente->rutadoc_expediente = $subFolderPath;
            $expediente->operacion_mantenimiento_id = $data['id_servicio'];
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

            return redirect()->route('expediente.operacion', ['slug' => $data['id_servicio']])
                ->with('generatedFiles', $generatedFiles);

        }

        catch (\Exception $e) {
            \Log::error("Error al generar documentos: " . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'No dejar campos sin rellenar']);
        }


    }

    public function generarActaVerificacion(Request $request){
       
        try{
        $rules = [
            'nomenclatura' => 'required',
            'idestacion' => 'required',
            'id_servicio' => 'required',
            'id_usuario' => 'required',
            'fecha_actual' => 'required|date',  
        

            'hora'=>'required',
            'hora_fin'=>'required',
            'recepcion'=>'required',
            'cargo'=>'required',
            'exten'=>'required',
            'num_telefono'=>'required',
            'correo'=>'required',

            'folio_testigo1'=>'required',
            'nom_testigo1'=>'required',
            'domicilio_testigo1'=>'required',

            'folio_testigo2'=>'required',
            'nom_testigo2'=>'required', 
            'domicilio_testigo2'=>'required', 

            'tipo_vialidad'=>'required', 
            'suma_tanques'=>'required', 
            'num_tanques'=>'required', 
            'num_tanques_diesel'=>'required', 
            'litros_diesel'=>'required', 
            'num_tanques_gaso'=>'required', 
            'litros_gasolina'=>'required', 
            'marca_tanque'=>'required', 
            'num_pozos'=>'required', 
            'num_pozos_moni'=>'required', 
            'num_techunbre'=>'required', 
            'num_columnas'=>'required', 
            'tipo_material'=>'required', 
            'num_despachos'=>'required', 
            'num_pro_diesel'=>'required', 
            'num_pro_gaso'=>'required', 
            'cuarto_sucios'=>'required', 
            'cuarto_maquinas'=>'required', 
            'cuarto_electrico'=>'required', 
            'trampas_sucios'=>'required', 
            'num_fases_sucios'=>'required', 
            'tubos_veteo'=>'required', 
            'lado_tubos'=>'required', 
            'si_no_anuncion'=>'required', 
            'almacen'=>'required', 
        ];
            $data = $request->validate($rules);
           


            $idEstacion = $request->input('idestacion');
            // Buscar la estación por su ID y obtener los datos necesarios
            $estacion = Estacion::findOrFail($idEstacion);

            $data['dirección_estacion'] = $estacion->domicilio_estacion_servicio;
            $data['domicilio_estacion'] = $estacion->domicilio_estacion_servicio;
            $data['razon_social'] = $estacion->razon_social;
            $data['cre']=$estacion->num_cre;
            
            $date = Carbon::parse($data['fecha_actual']);

            $data['dia']=$date->month;
            $data['mes']=$date->day;
            $data['año']=$date->year;

           
            


            $usuario=User::find($data['id_usuario']);
            
            $data['inspector']=$usuario->name;
            $data['nom_inspector']=$usuario->name;
        
            $templatePaths = [             
                'ACTA VERIFICACIÓN O.M. V3.docx',
            ];
            // Definir la carpeta de destino
            $customFolderPath = "OperacionyMantenimiento/{$data['nomenclatura']}";
            $subFolderPath = "{$customFolderPath}/expediente";

            // Crear la carpeta personalizada si no existe
            if (!Storage::disk('public')->exists($customFolderPath)) {
                Storage::disk('public')->makeDirectory($customFolderPath);
            }

            // Verificar y crear la subcarpeta si no existe
            if (!Storage::disk('public')->exists($subFolderPath)) {
                Storage::disk('public')->makeDirectory($subFolderPath);
            }
            $fecha_actual = Carbon::createFromFormat('Y-m-d', $data['fecha_actual'])->format('d-m-Y');
    


            foreach ($templatePaths as $templatePath) {
                $templateProcessor = new TemplateProcessor(storage_path("app/templates/OperacionyMantenimiento/{$templatePath}"));
  
                // Reemplazar todos los marcadores con los datos del formulario
                foreach ($data as $key => $value) {
                    $templateProcessor->setValue($key, $value);
                    // Reemplazar fechas formateadas específicas
                    $templateProcessor->setValue('fecha_actual', $fecha_actual);
                 
                }

                // Crear un nombre de archivo basado en la nomenclatura
                $fileName = pathinfo($templatePath, PATHINFO_FILENAME) . "_{$data['nomenclatura']}.docx";

                // Guardar la plantilla procesada en la carpeta de destino
                $templateProcessor->saveAs(storage_path("app/public/{$subFolderPath}/{$fileName}"));
            }


            $acta = Acta_Operacion::firstOrNew(['servicio_id' => $data['id_servicio']]);
            $acta->rutadoc_acta = $subFolderPath;
            $acta->servicio_id= $data['id_servicio'];
            $acta->save();

            $generatedFiles = array_map(function ($templatePath) use ($subFolderPath, $data) {
                $fileName = pathinfo($templatePath, PATHINFO_FILENAME) . "_{$data['nomenclatura']}.docx";
                return [
                    'name' => $fileName,
                    'url' => Storage::url("{$subFolderPath}/{$fileName}"),
                ];
            }, $templatePaths);

            return redirect()->route('expediente.operacion', ['slug' => $data['id_servicio']])
                ->with('generatedFiles', $generatedFiles);




        }
 catch (\Exception $e) {
            \Log::error("Error al generar documentos: " . $e->getMessage());
            return response()->json(['error' => 'Ocurrió un error al procesar la solicitud. Por favor, intenta de nuevo más tarde.'], 500);
        }

    }


}