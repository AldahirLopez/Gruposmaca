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
        $estacion=ServicioAnexo::find($servicio_anexo_id);
        $archivoAnexo = Datos_Servicio::where('servicio_anexo_id', $servicio_anexo_id)->first();

        return view('armonia.anexo.servicio_anexo.archivos_anexo.index', compact('archivoAnexo', 'estados', 'servicio_anexo_id','estacion'));
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
        'estado' => 'required',
        'contacto' => 'required|string|max:255',
        'nom_repre' => 'required|string|max:255',
        'fecha_inspeccion' => 'required',
    ]);

    // Cargar la plantilla de Word con marcadores de posición
    $templatePath = storage_path('app/templates/ORDEN DE TRABAJO.docx');
    $templateProcessor = new TemplateProcessor($templatePath);

    // Reemplazar los marcadores de posición con los datos del formulario
    foreach ($data as $key => $value) {
        $templateProcessor->setValue($key, $value);
    }

    // Guardar el documento generado
    $fileName = 'formato_rellenado.docx';
    $filePath = storage_path($fileName);
    $templateProcessor->saveAs($filePath);

    // Descargar el archivo
    return response()->download($filePath)->deleteFileAfterSend(true);
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
