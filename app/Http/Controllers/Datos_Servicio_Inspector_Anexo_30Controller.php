<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Datos_Servicio;
use App\Models\ServicioAnexo;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // Importa la clase Auth
use Carbon\Carbon;



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

            //Si es administrador o auditor puede ver todo y editar todo 
            $servicio_anexo_id = $slug;
            $estacion = ServicioAnexo::find($servicio_anexo_id);
            $archivoAnexo = Datos_Servicio::where('servicio_anexo_id', $servicio_anexo_id)->first();

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

            //Si es administrador o auditor puede ver todo y editar todo 
            $servicio_anexo_id = $slug;
            $estacion = ServicioAnexo::find($servicio_anexo_id);
            $validar_servicio = ($estacion->usuario_id == $usuario->id);
            //$validar_usuario =  User::where('usuario_id',)

            if ($validar_servicio) {
                $archivoAnexo = Datos_Servicio::where('servicio_anexo_id', $servicio_anexo_id)->first();
                //Si es administrador o auditor puede ver todo y editar todo 
                $servicio_anexo_id = $slug;
                $estacion = ServicioAnexo::find($servicio_anexo_id);
                $archivoAnexo = Datos_Servicio::where('servicio_anexo_id', $servicio_anexo_id)->first();

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

                return redirect()->route('servicio_anexo_30.datos_servicio_anexo.index')->with('error', 'Servicio no valido');
            }

        }
    }

    public function generateWord(Request $request)
    {
        try {
            // Validar los datos del formulario
            $data = $request->validate([
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

            // Convertir las fechas al formato deseado para almacenamiento y manipularlas
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

            // Definir la carpeta de destino dentro de 'public/storage'
            $customFolderPath = "servicios_anexo30/{$data['nomenclatura']}";
            $subFolderPath = "{$customFolderPath}/documentos_rellenados/";

            if (!Storage::disk('public')->exists($customFolderPath)) {
                Storage::disk('public')->makeDirectory($customFolderPath);
            }

            // Verificar y crear la subcarpeta dentro de la carpeta principal si no existe
            if (!Storage::disk('public')->exists($subFolderPath)) {
                Storage::disk('public')->makeDirectory($subFolderPath);
            }

            // Reemplazar marcadores en todas las plantillas
            foreach ($templatePaths as $templatePath) {
                $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor(storage_path("app/templates/formatos_anexo30/{$templatePath}"));

                // Reemplazar todos los marcadores con los datos del formulario
                foreach ($data as $key => $value) {
                    $templateProcessor->setValue($key, $value);
                    // Reemplazar fechas formateadas específicas
                    $templateProcessor->setValue('fecha_inspeccion', $fechaInspeccion);
                    $templateProcessor->setValue('fecha_recepcion', $fechaRecepcion);
                    $templateProcessor->setValue('fecha_inspeccion_modificada', $fechaInspeccionAumentada); // Para las plantillas que necesitan la fecha aumentada
                }

                // Crear un nombre de archivo basado en la nomenclatura
                $fileName = pathinfo($templatePath, PATHINFO_FILENAME) . "_{$data['nomenclatura']}.docx";

                // Guardar la plantilla procesada
                $templateProcessor->saveAs(storage_path("app/public/{$subFolderPath}/{$fileName}"));
            }

            // Recuperar o crear el registro en Datos_Servicio
            $datosServicio = Datos_Servicio::firstOrNew(['servicio_anexo_id' => $data['id_servicio']]);

            // Asignar los datos del formulario al modelo
            $datosServicio->Razon_Social = $data['razonsocial'];
            $datosServicio->RFC = $data['rfc'];
            $datosServicio->Domicilio_Fiscal = $data['domicilio_fiscal'];
            $datosServicio->Telefono = $data['telefono'];
            $datosServicio->Correo = $data['correo'];
            $datosServicio->Fecha_Recepcion_Solicitud = $data['fecha_recepcion'];
            $datosServicio->Num_CRE = $data['cre'];
            $datosServicio->Num_Constancia = $data['constancia'];
            $datosServicio->Domicilio_Estacion_Servicio = $data['domicilio_estacion'];
            $datosServicio->Estado_Estacion = $data['estado'];
            $datosServicio->Contacto = $data['contacto'];
            $datosServicio->Nombre_Representante_Legal = $data['nom_repre'];
            $datosServicio->Fecha_Inspeccion = $data['fecha_inspeccion'];
            $datosServicio->servicio_anexo_id = $data['id_servicio'];

            // Guardar el objeto en la base de datos
            $datosServicio->save();

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


}