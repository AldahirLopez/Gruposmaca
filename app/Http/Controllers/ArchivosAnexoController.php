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

class ArchivosAnexoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
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

        $servicio_anexo_id = $request->servicio_anexo_id;
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

        return view('armonia.anexo.servicio_anexo.archivos_anexo.index', compact('archivoAnexo', 'estados', 'servicio_anexo_id', 'estacion', 'existingFiles'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function generateWord(Request $request)
    {
        // Validar los datos del formulario
        $data = $request->validate([
            'servicio_anexo_id' => 'required',
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

        // Cargar las plantillas de Word
        $templatePath = storage_path('app/templates/formatos_anexo30/ORDEN DE TRABAJO.docx');
        $templatePath1 = storage_path('app/templates/formatos_anexo30/FORMATO PARA CONTRATO DE PRESTACIÓN DE SERVICIOS DE INSPECCIÓN DE LOS ANEXOS 30 Y 31 RESOLUCIÓN MISCELÁNEA FISCAL PARA 2024.docx');
        $templatePath2 = storage_path('app/templates/formatos_anexo30/FORMATO DE DETECCIÓN DE RIESGOS A LA IMPARCIALIDAD.docx');
        $templatePath3 = storage_path('app/templates/formatos_anexo30/PLAN DE INSPECCIÓN DE PROGRAMAS INFORMATICOS.docx');

        // Crear los procesadores de plantillas
        $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
        $templateProcessor1 = new \PhpOffice\PhpWord\TemplateProcessor($templatePath1);
        $templateProcessor2 = new \PhpOffice\PhpWord\TemplateProcessor($templatePath2);
        $templateProcessor3 = new \PhpOffice\PhpWord\TemplateProcessor($templatePath3);

        // Reemplazar los marcadores de posición con los datos del formulario
        foreach ($data as $key => $value) {
            $templateProcessor->setValue($key, $value);
            $templateProcessor1->setValue($key, $value);
            $templateProcessor2->setValue($key, $value);
            $templateProcessor3->setValue($key, $value);
        }

        // Definir la carpeta de destino dentro de 'public/storage'
        $customFolderPath = "servicios_anexo30/{$data['servicio_anexo_id']}";

        // Verificar si la carpeta principal existe
        if (Storage::disk('public')->exists($customFolderPath)) {
            // La carpeta principal existe, crear una subcarpeta dentro de ella
            $subFolderPath = "{$customFolderPath}/formatos_rellenados_anexo30";

            if (!Storage::disk('public')->exists($subFolderPath)) {
                // Crear la subcarpeta
                Storage::disk('public')->makeDirectory($subFolderPath);
            }
        }

        // Definir los nombres de los archivos y sus rutas completas dentro de 'public/storage'
        $fileName = "ORDEN DE TRABAJO_RELLENADO.docx";
        $fileName1 = "FORMATO PARA CONTRATO DE PRESTACIÓN DE SERVICIOS DE INSPECCIÓN DE LOS ANEXOS 30 Y 31 RESOLUCIÓN MISCELÁNEA FISCAL PARA 2024_RELLENADO.docx";
        $fileName2 = "FORMATO DE DETECCIÓN DE RIESGOS A LA IMPARCIALIDAD_RELLENADO.docx";
        $fileName3 = "PLAN DE INSPECCIÓN DE PROGRAMAS INFORMATICOS_RELLENADO.docx";

        // Guardar los archivos generados
        $templateProcessor->saveAs(storage_path("app/public/$subFolderPath/$fileName"));
        $templateProcessor1->saveAs(storage_path("app/public/$subFolderPath/$fileName1"));
        $templateProcessor2->saveAs(storage_path("app/public/$subFolderPath/$fileName2"));
        $templateProcessor3->saveAs(storage_path("app/public/$subFolderPath/$fileName3"));

        // Crear la lista de archivos generados con sus URLs
        $generatedFiles = [
            [
                'name' => $fileName,
                'url' => Storage::url("$subFolderPath/$fileName"),
            ],
            [
                'name' => $fileName1,
                'url' => Storage::url("$subFolderPath/$fileName1"),
            ],
            [
                'name' => $fileName2,
                'url' => Storage::url("$subFolderPath/$fileName2"),
            ],
            [
                'name' => $fileName3,
                'url' => Storage::url("$subFolderPath/$fileName3"),
            ]
        ];

        

        // Retornar respuesta JSON con los archivos generados
        return response()->json(['generatedFiles' => $generatedFiles]);
    }





    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
