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
    public function generarpdfcotizacion(Request $request)
    {
        // Validar los datos del formulario
        $data = $request->validate([
            'nomenclatura' => 'required',
            'nombre_estacion' => 'required|string|max:255',
            'direccion_estacion' => 'required|string|max:255',
            'estado_estacion' => 'required|string|max:255',
            'costo' => 'required|numeric',
        ]);

        // Establecer la configuración regional en español
        app()->setLocale('es');

        // Obtener los datos del formulario
        $nomenclatura = $data['nomenclatura'];
        $nombre_estacion = $data['nombre_estacion'];
        $direccion_estacion = $data['direccion_estacion'];
        $estado_estacion = $data['estado_estacion'];
        $costo = $data['costo'];

        // Calcular el 16% de IVA
        $iva = $costo * 0.16;

        // Obtener la fecha actual en el formato deseado (día de mes de año)
        $fecha_actual = Carbon::now()->formatLocalized('%A %d de %B de %Y');

        // Ruta de la carpeta donde se guardarán los PDFs
        $folderPath = "public/servicios_anexo30/{$nomenclatura}";
        $pdfPath = "{$folderPath}/cotizacion/{$nomenclatura}.pdf"; // Ruta completa del PDF

        // Verificar si la carpeta existe, si no, crearla
        if (!Storage::exists($folderPath)) {
            Storage::makeDirectory($folderPath);
        }

        // Pasar los datos al PDF y renderizarlo, incluyendo la fecha actual
        $html = view('armonia.anexo.cotizacion.cotizacion_pdf.cotizacion', compact('nombre_estacion', 'direccion_estacion', 'estado_estacion', 'costo', 'iva', 'fecha_actual'))->render();
        $pdf = PDF::loadHTML($html);

        // Guardar el PDF en el almacenamiento de Laravel
        Storage::put($pdfPath, $pdf->output());

        // Obtener la URL pública del PDF
        $pdfUrl = Storage::url($pdfPath);

        // Devolver la URL del PDF como respuesta
        return response()->json(['pdf_url' => $pdfUrl]);
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
