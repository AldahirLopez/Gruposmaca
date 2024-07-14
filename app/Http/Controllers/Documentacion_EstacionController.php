<?php

namespace App\Http\Controllers;

use App\Models\Documento_Estacion;
use App\Models\Documento_Estacion_Operacion;
use App\Models\Estacion;
use App\Http\Controllers\Controller;
use App\Models\Usuario_Estacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;



use Illuminate\Support\Facades\Auth; // Importa la clase Auth

class Documentacion_EstacionController extends Controller
{

    protected $connection = 'segunda_db';

    public function index(Request $request)
    {
        try {
            if ($request->has('id')) {
                $id = $request->input('id');

                // Obtener la ruta de la carpeta de documentos para esta estación
                $estacion = Estacion::findOrFail($id);

                // Pasar los datos a la vista
                return view('armonia.documentos_estacion.index', compact('estacion'));
            } else {
                return redirect()->route('documentacion_estacion.index')->with('error', 'No se proporcionó un ID de estación.');
            }
        } catch (\Exception $e) {
            return redirect()->route('documentacion_estacion.index')->with('error', 'Error al obtener la documentación: ' . $e->getMessage());
        }
    }
    public function DocumentacionOperacion(Request $request)
    {
        try {
            if ($request->has('id')) {
                $id = $request->input('id');
                $estacion = Estacion::findOrFail($id);
                $razonSocial = str_replace([' ', '.'], '_', $estacion->razon_social);
                $customFolderPath = "armonia/estaciones/{$razonSocial}/documentacion/operacionymantenimiento";

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

                return view('armonia.documentos_estacion.DocumentosOperacion', compact('requiredDocuments', 'documentos', 'id', 'estacion'));
            } else {
                return redirect()->route('documentacion_estacion.index')->with('error', 'No se proporcionó un ID de estación.');
            }
        } catch (\Exception $e) {
            return redirect()->route('documentacion_estacion.index')->with('error', 'Error al obtener la documentación: ' . $e->getMessage());
        }
    }


    public function storeoperacion(Request $request)
    {
        $data = $request->validate([
            'rutadoc_estacion' => 'required|file',
            'estacion_id' => 'required',
            'razon_social' => 'required',
            'nombre' => 'required',
        ]);

        try {
            $documento = Documento_Estacion_Operacion::firstOrNew(['estacion_id' => $data['estacion_id']]);

            if ($request->hasFile('rutadoc_estacion')) {
                $archivoSubido = $request->file('rutadoc_estacion');
                $nombreArchivoPersonalizado = $data['nombre'] . '.' . $archivoSubido->getClientOriginalExtension();

                $razonSocial = str_replace([' ', '.'], '_', $data['razon_social']);
                $customFolderPath = "armonia/estaciones/{$razonSocial}/documentacion/operacionymantenimiento";

                if (!Storage::disk('public')->exists($customFolderPath)) {
                    Storage::disk('public')->makeDirectory($customFolderPath);
                }

                $rutaArchivo = $archivoSubido->storeAs("public/{$customFolderPath}", $nombreArchivoPersonalizado);

                $documento->rutadoc_estacion = str_replace('public/', '', $customFolderPath);
            }

            $documento->estacion_id = $data['estacion_id'];
            $documento->usuario_id = Auth::id();
            $documento->save();

            return redirect()->route('documentacion_operacion', ['id' => $data['estacion_id']])->with('success', 'Documento guardado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('documentacion_operacion', ['id' => $data['estacion_id']])->with('error', 'Documento no guardado exitosamente.');
        }
    }
}
