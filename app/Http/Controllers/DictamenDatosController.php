<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estacion;
use App\Models\Equipo;
use App\Models\Tanque;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DictamenDatosController extends Controller
{
    public function create()
    {
        return view('armonia.dictamen_datos.create');
    }

    public function store(Request $request)
    {
        $estacion = Estacion::where('num_estacion', $request->input('num_estacion'))->first();
        $dispensarios = $request->input('dispensarios', []);
        $sondas = $request->input('sondas', []);
        $combustibles = $request->input('combustibles', []);

        // Depuración: Imprimir datos recibidos
        \Log::info('Datos recibidos:', [
            'combustibles' => $combustibles,
        ]);

        $numSeriesEquipos = [];
        $usuario = Auth::user();

        if (!$dispensarios || !$sondas) {
            return redirect()->route('dictamen_datos.create')->with('error', 'Rellenar todos los campos');
        }
        // Crear o actualizar dispensarios y sondas
        foreach ($dispensarios as $dispensario) {
            $equipo = Equipo::updateOrCreate(
                ['num_serie' => $dispensario['numero_serie']],
                [
                    'modelo' => $dispensario['modelo'],
                    'marca' => $dispensario['marca'],
                    'tipo' => "Dispensario",
                ]
            );
            $numSeriesEquipos[] = $dispensario['numero_serie'];
        }

        foreach ($sondas as $sonda) {
            $equipo = Equipo::updateOrCreate(
                ['num_serie' => $sonda['numero_serie']],
                [
                    'modelo' => $sonda['modelo'],
                    'marca' => $sonda['marca'],
                    'tipo' => "Sonda",
                ]
            );
            $numSeriesEquipos[] = $sonda['numero_serie'];
        }

        // Recuperar tanques
        $tanques = Tanque::whereIn('nombre', ['Diesel', 'Premium', 'Magna'])->get()->keyBy('nombre');

        // Preparar datos para la tabla pivote
        $pivotData = [];
        foreach ($request->input('combustibles', []) as $combustible) {
            $tipoTanque = ucfirst($combustible['tipo']); // Capitalizar el primer carácter
            $cantidad = $combustible['cantidad'];

            if (isset($tanques[$tipoTanque])) {
                $tanqueId = $tanques[$tipoTanque]->id;
                // Insertar cada registro como una fila separada en la tabla pivote
                $pivotData[] = [
                    'id_estacion' => $estacion->id,
                    'id_tanque' => $tanqueId,
                    'capacidad' => $cantidad,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Depuración final de los datos procesados
        \Log::info('Datos de pivote:', $pivotData);

        // Usar DB::connection para especificar la conexión a la base de datos 'armonia'
        // Primero, eliminar los registros antiguos
        DB::connection('segunda_db')->table('estacion_tanque')->where('id_estacion', $estacion->id)->delete();

        // Insertar los nuevos datos
        DB::connection('segunda_db')->table('estacion_tanque')->insert($pivotData);

        // Primero, eliminar las asociaciones antiguas
        DB::connection('segunda_db')->table('equipo_estacion')->where('id_estacion', $estacion->id)->delete();

        // Insertar las nuevas asociaciones
        DB::connection('segunda_db')->table('equipo_estacion')->insert(
            array_map(fn($numSerie) => ['id_equipo' => $numSerie, 'id_estacion' => $estacion->id], $numSeriesEquipos)
        );

        return redirect()->route('dictamen_datos.create')->with('success', 'Estación registrada correctamente');
    }


}

