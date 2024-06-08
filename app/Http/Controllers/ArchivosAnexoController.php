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
        $templatePath = storage_path('app/templates/ORDEN DE TRABAJO.docx');
        $templatePath1 = storage_path('app/templates/FORMATO PARA CONTRATO DE PRESTACIÓN DE SERVICIOS DE INSPECCIÓN DE LOS ANEXOS 30 Y 31 RESOLUCIÓN MISCELÁNEA FISCAL PARA 2024.docx');
        $templateProcessor = new TemplateProcessor($templatePath);
        $templateProcessor1 = new TemplateProcessor($templatePath1);

        // Reemplazar los marcadores de posición con los datos del formulario
        foreach ($data as $key => $value) {
            $templateProcessor->setValue($key, $value);
            $templateProcessor1->setValue($key, $value);
        }

        // Guardar los documentos generados
        $fileName = 'formato_rellenado.docx';
        $fileName1 = 'formato_rellenado2.docx';
        $filePath = storage_path("app/public/$fileName");
        $filePath1 = storage_path("app/public/$fileName1");
        $templateProcessor->saveAs($filePath);
        $templateProcessor1->saveAs($filePath1);

        // Comprimir ambos archivos en un solo ZIP para descarga
        $zipFileName = 'documentos_rellenados.zip';
        $zipFilePath = storage_path("app/public/$zipFileName");

        $zip = new \ZipArchive();
        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $zip->addFile($filePath, $fileName);
            $zip->addFile($filePath1, $fileName1);
            $zip->close();
        }

        // Eliminar los archivos individuales ya que están en el ZIP
        unlink($filePath);
        unlink($filePath1);

        // Descargar el archivo ZIP y eliminar después de enviar
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
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
