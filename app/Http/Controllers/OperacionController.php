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
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
//Este controlador se va utilizar para la parte del administrador donde aprueba los servicios de operacion y mantenimiento

class OperacionController extends Controller
{

    protected $connection = 'segunda_db';

    function __construct()
    {
        $this->middleware('permission:ver-servicio_operacion_mantenimiento|crear-servicio_operacion_mantenimiento|editar-servicio_operacion_mantenimiento|borrar-servicio_operacion_mantenimiento', ['only' => ['index']]);
        $this->middleware('permission:crear-servicio_operacion_mantenimiento', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-servicio_operacion_mantenimiento', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-servicio_operacion_mantenimiento', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */     public function index()
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
                ->table('operacion_mantenimiento')
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



    //Metodo para filtrar por estado ,año y usuario
    public function obtenerServicios(Request $request)
    {
        $usuarioSeleccionado = $request->input('usuario_id');
        $estadoSeleccionado=$request->input('estado');
        $yearSeleccionado=$request->input('year');
      
    
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

            if($usuarioSeleccionado==="todos"){
                $servicios=ServicioOperacion::all();
            }
            $usuario=User::find($usuarioSeleccionado);
         
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
           return redirect()->route('servicio_inspector_anexo_30.index')->with(['servicios' => $servicios,'año'=>$yearSeleccionado,'estado'=>$estadoSeleccionado,'usuario'=>$usuario]);
          

        
    }


    
}
