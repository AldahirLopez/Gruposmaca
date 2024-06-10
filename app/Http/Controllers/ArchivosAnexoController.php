<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Datos_Servicio;
use App\Models\ServicioAnexo;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\IOFactory;

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

        return view('armonia.anexo.servicio_anexo.archivos_anexo.index', compact('archivoAnexo', 'estados', 'servicio_anexo_id', 'estacion'));
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
        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor1 = new TemplateProcessor($templatePath1);
        $templateProcessor2 = new TemplateProcessor($templatePath2);
        $templateProcessor3 = new TemplateProcessor($templatePath3);

        // Reemplazar los marcadores de posición con los datos del formulario
        foreach ($data as $key => $value) {
            $templateProcessor->setValue($key, $value);
            $templateProcessor1->setValue($key, $value);
            $templateProcessor2->setValue($key, $value);
            $templateProcessor3->setValue($key, $value);
        }

        // Guardar los documentos generados
        $fileName = "ORDEN DE TRABAJO_RELLENADO.docx";
        $fileName1 = "FORMATO PARA CONTRATO DE PRESTACIÓN DE SERVICIOS DE INSPECCIÓN DE LOS ANEXOS 30 Y 31 RESOLUCIÓN MISCELÁNEA FISCAL PARA 2024_RELLENADO.docx";
        $fileName2 = "FORMATO DE DETECCIÓN DE RIESGOS A LA IMPARCIALIDAD_RELLENADO.docx";
        $fileName3 = "PLAN DE INSPECCIÓN DE PROGRAMAS INFORMATICOS.docx";
        $filePath = storage_path("app/public/formatos_anexo30_rellenados/$fileName");
        $filePath1 = storage_path("app/public/formatos_anexo30_rellenados/$fileName1");
        $filePath2 = storage_path("app/public/formatos_anexo30_rellenados/$fileName2");
        $filePath3 = storage_path("app/public/formatos_anexo30_rellenados/$fileName3");
        $templateProcessor->saveAs($filePath);
        $templateProcessor1->saveAs($filePath1);
        $templateProcessor2->saveAs($filePath2);
        $templateProcessor3->saveAs($filePath3);

        // Crear la lista de archivos generados
        $generatedFiles = [
            [
                'name' => $fileName,
                'url' => asset("storage/formatos_anexo30_rellenados/$fileName"),
            ],
            [
                'name' => $fileName1,
                'url' => asset("storage/formatos_anexo30_rellenados/$fileName1"),
            ],
            [
                'name' => $fileName2,
                'url' => asset("storage/formatos_anexo30_rellenados/$fileName2"),
            ],
            [
                'name' => $fileName3,
                'url' => asset("storage/formatos_anexo30_rellenados/$fileName3"),
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
