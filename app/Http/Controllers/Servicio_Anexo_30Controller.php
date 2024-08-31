<?php

namespace App\Http\Controllers;

use App\Models\Factura_Anexo;
use App\Models\Pago_Anexo;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Cotizacion_Servicio_Anexo30;
use App\Models\ServicioAnexo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

use Spatie\Permission\Models\Role;
use App\Models\ServicioOperacion;

use Illuminate\Support\Facades\Auth; // Importa la clase Auth

class Servicio_Anexo_30Controller extends Controller
{

    protected $connection = 'segunda_db';

    function __construct()
    {

        //COTIZACION
        $this->middleware('permission:Generar-cotizacion-anexo_30', ['only' => ['generarpdfcotizacion']]);
        $this->middleware('permission:Descargar-cotizacion-anexo_30', ['only' => ['descargarCotizacionAjax']]);
        //PAGOS
        $this->middleware('permission:Ver-pagos-anexo_30', ['only' => ['pagosAnexo']]);
        $this->middleware('permission:Subir-pago-anexo_30', ['only' => ['storePagoAnexo']]);
        $this->middleware('permission:Descargar-pago-anexo_30', ['only' => ['descargarPagoAnexo']]);
        //FACTURA
        $this->middleware('permission:Subir-factura-anexo_30', ['only' => ['storeFactura']]);
        $this->middleware('permission:Descargar-factura-anexo_30', ['only' => ['descargarFacturaAnexo']]);
    }
    /**
     * Display a listing of the resource.
     */


    public function index()
    {
        //Vista inicial de anexo 30 divida en tarjetas
        return view('armonia.servicio_anexo_30.index');
    }

    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function apro_servicio_anexo()
    {
        $usuario = Auth::user();

        if (auth()->check() && $usuario->hasAnyRole(['Administrador', 'Auditor'])) {
            $servicios = ServicioAnexo::with('estacionServicios.direccionFiscal', 'estacionServicios.direccionServicio')->get();
        } else {
            $servicios = ServicioAnexo::where('usuario_id', $usuario->id)
                ->with('estacionServicios.direccionFiscal', 'estacionServicios.direccionServicio')
                ->get();
        }

        return view('armonia.servicio_anexo_30.aprobacion_servicio.index', compact('servicios'));
    }

    public function apro($id)
    {
        try {
            // Buscar el servicio por su ID
            $servicio = ServicioAnexo::findOrFail($id);

            // Establecer pending_apro_servicio como true
            $servicio->pending_apro_servicio = true;
            $servicio->save();

            // Redireccionar con un mensaje de éxito
            return redirect()->route('apro.anexo')->with('success', 'Servicio aprobado correctamente.');
        } catch (ModelNotFoundException $e) {
            // Manejar la excepción si el servicio no se encuentra
            return redirect()->route('apro.anexo') > with('error', 'Servicio no encontrado.');
        }
    }

    //Metodo para generar el pdf de la cotizacion
    public function generarpdfcotizacion(Request $request)
    {
        // Establecer la configuración regional en español
        app()->setLocale('es');

        // Obtener los datos del formulario
        $id_servicio = $request->input('id_servicio');
        $nomenclatura = $request->input('nomenclatura');
        $nombre_estacion = strtoupper($request->input('razon_social'));

        // Obtener los componentes individuales de la dirección
        $calle = strtoupper($request->input('calle'));
        $numero_ext = strtoupper($request->input('numero_ext'));
        $numero_int = strtoupper($request->input('numero_int'));
        $colonia = strtoupper($request->input('colonia'));
        $codigo_postal = strtoupper($request->input('codigo_postal'));
        $municipio = strtoupper($request->input('municipio'));
        $entidad_federativa = strtoupper($request->input('entidad_federativa'));

        // Combinar los componentes de la dirección en una sola cadena
        $direccion_estacion = "Calle: $calle, Número Ext: $numero_ext $numero_int, Colonia: $colonia, C.P.: $codigo_postal, Municipio: $municipio, Entidad Federativa: $entidad_federativa";

        $costo = $request->input('costo');

        // Calcular el 16% de IVA
        $iva = $costo * 0.16;

        // Obtener la fecha actual en el formato deseado (día de mes de año)
        $fecha_actual = Carbon::now()->formatLocalized('%A %d de %B de %Y');

        // Definir la carpeta principal y la subcarpeta donde se guardarán los PDFs
        $folderPath = "servicios_anexo30/{$nomenclatura}";
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
        $html = view('armonia.servicio_anexo_30.cotizacion_pdf.cotizacion', compact('nombre_estacion', 'direccion_estacion', 'costo', 'iva', 'fecha_actual'))->render();
        $pdf = PDF::loadHTML($html);

        // Guardar el PDF en el almacenamiento de Laravel
        Storage::disk('public')->put($pdfPath, $pdf->output());

        // Obtener la URL pública del PDF
        $pdfUrl = Storage::url($pdfPath);

        // Verificar si ya existe una cotización con este id_servicio
        $cotizacion = Cotizacion_Servicio_Anexo30::where('servicio_anexo_id', $id_servicio)->first();

        if ($cotizacion) {
            // Si ya existe, actualiza el registro existente
            $cotizacion->rutadoc_cotizacion = $pdfUrl;
            $cotizacion->save();
        } else {
            // Si no existe, crea un nuevo registro
            $cotizacion = new Cotizacion_Servicio_Anexo30();
            $cotizacion->rutadoc_cotizacion = $pdfUrl;
            $cotizacion->servicio_anexo_id = $id_servicio;
            $cotizacion->estado_cotizacion = true;
            // Asigna otros campos si es necesario
            $cotizacion->save();
        }

        // Devolver la URL del PDF como respuesta
        Log::info($pdfUrl);

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

    public function pagosAnexo()
    {

        $pagos = Pago_Anexo::all();

        return view('armonia.servicio_anexo_30.pagos.index', compact('pagos'));
    }

    public function storePagoAnexo(Request $request)
    {
        $data = $request->validate([
            'rutadoc' => 'required|file|mimes:pdf',
            'servicio_id' => 'required',
            'nomenclatura' => 'required',
        ]);


        try {
            $pago = Pago_Anexo::firstOrNew(['servicio_anexo_id' => $data['servicio_id']]);
            $pago->comentarios = "";
            if ($request->hasFile('rutadoc')) {
                $archivoSubido = $request->file('rutadoc');

                $nombreArchivoPersonalizado = "Pago_" . $data['nomenclatura'] . '.' . $archivoSubido->getClientOriginalExtension();

                $nomenclatura = $data['nomenclatura'];

                // Definir la carpeta principal y la subcarpeta donde se guardarán los PDFs
                $folderPath = "Servicios_Anexo30/{$nomenclatura}";
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

            $pago->servicio_anexo_id = $data['servicio_id'];
            $pago->estado_pago = false;
            $pago->save();

            return redirect()->route('servicio_inspector_anexo_30.index', ['id' => $data['servicio_id']])->with('success', 'Pago guardado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('servicio_inspector_anexo_30.index', ['id' => $data['servicio_id']])->with('error', 'Pago no guardado exitosamente.');
        }
    }
    public function descargarPagoAnexo(Request $request)
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

    public function storeFacturaAnexo(Request $request)
    {

        $data = $request->validate([
            'rutadoc' => 'required|file|mimes:pdf',
            'servicio_id' => 'required',
            'nomenclatura' => 'required',
        ]);

        $usuario = Auth::user();

        try {
            $pago = Pago_Anexo::where('servicio_anexo_id', $data['servicio_id'])->first();

            if (!$pago) {
                return redirect()->route('pagosAnexo.index', ['id' => $data['servicio_id']])->with('error', 'Pago no encontrado.');
            }

            $factura = Factura_Anexo::firstOrNew(['id_pago' => $pago->id]);

            if ($request->hasFile('rutadoc')) {
                $archivoSubido = $request->file('rutadoc');

                $nombreArchivoPersonalizado = "Factura_" . $data['nomenclatura'] . '.' . $archivoSubido->getClientOriginalExtension();

                $nomenclatura = $data['nomenclatura'];

                // Definir la carpeta principal y la subcarpeta donde se guardarán los PDFs
                $folderPath = "Servicios_Anexo30/{$nomenclatura}";
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

                $factura->ruta_pdf = $pdfUrl;
                $factura->rutad_xml = $pdfUrl;
            }

            $pago->estado_pago = true;
            $pago->save();

            $factura->id_pago = $pago->id;
            $factura->usuario_id = $usuario->id;
            $factura->save();

            return redirect()->route('pagosAnexo.index', ['id' => $data['servicio_id']])->with('success', 'Factura guardada exitosamente.');
        } catch (\Exception $e) {
            \Log::error('Error guardando la factura: ' . $e->getMessage());
            return redirect()->route('pagosAnexo.index', ['id' => $data['servicio_id']])->with('error', 'Factura no guardada exitosamente.');
        }
    }

    public function descargarFacturaAnexo(Request $request)
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
}
