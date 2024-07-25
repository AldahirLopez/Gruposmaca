<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estacion;
use App\Models\Equipo;
use App\Models\Tanque;
use App\Models\Control_Volumetrico;
use Illuminate\Support\Facades\Auth;

class DictamenDatosController extends Controller
{
   
   
   
    public function create(){

        return view('armonia.dictamen_datos.create');
    }


    
    public function store(Request $request){


        $estacion = Estacion::where('num_estacion', request('num_estacion'))->first();
        $dispensarios = $request->input('dispensarios', []);
        $sondas = $request->input('sondas', []);

        $id_equipos=[];
        $id_tanques=[];
        $usuario = Auth::user();
        if(!$dispensarios or  !$sondas){
            return redirect()->route('dictamen_datos.create')->with('error', 'Rellenar todos los campos');
        }
        
        if(!$estacion){

            //Aqui creamos la estacion para asignarle sus equipos
            $estacion = Estacion::create([
                'num_estacion' => $request->input('num_estacion'),
                'razon_social' => $request->input('razon_social'),
                'rfc' => $request->input('rfc'),
                'domicilio_fiscal' => $request->input('domicilio_instalacion'),
                'domicilio_estacion_servicio' => $request->input('domicilio_instalacion'),
                'estado_republica_estacion' => "",
                'num_cre' => $request->input('cre'),
                'num_constancia' => "",
                'correo_electronico' => $request->input('correo'),
                'contacto' => $request->input('telefono'),
                'nombre_representante_legal' => $request->input('responsable_sgm'),
                'usuario_id' =>$usuario->id, // Asegúrate de que este campo está presente y correcto
            ]);
            
          
            foreach ($dispensarios as $dispensario ) {             
                $equipo=Equipo::find($dispensario['numero_serie']);
                
                if(!$equipo){
                     
                    $equipo = Equipo::create([
                        'num_serie' => $dispensario['numero_serie'],
                        'modelo' => $dispensario['modelo'],
                        'marca' => $dispensario['marca'],
                        'tipo' => "Dispensario",
                    ]);
                    
                   
                }
                $id_equipos[]=$dispensario['numero_serie'];         
               
            }


            foreach ($sondas as $sonda ) {             
                $equipo=Equipo::find($sonda['numero_serie']);
            
                if(!$equipo){
                     
                    $equipo = Equipo::create([
                        'num_serie' => $sonda['numero_serie'],
                        'modelo' => $sonda['modelo'],
                        'marca' => $sonda['marca'],
                        'tipo' => "Sonda",
                    ]);
                    
                   
                }
                $id_equipos[]=$sonda['numero_serie'];         
               
            }
           

            $diesel=Tanque::where('nombre','Diesel')->first();
            $premium=Tanque::where('nombre','Premium')->first();
            $magna=Tanque::where('nombre','Magna')->first();
            


            $tanques = collect([
                [
                    'id' => $diesel->id,
                    'capacidad' => $request->input('Diesel'),
                ],
                [
                    'id' => $premium->id,
                    'capacidad' => $request->input('Premium'),
                ],
                [
                    'id' => $magna->id,
                    'capacidad' => $request->input('Magna'),
                ],
            ]);

          
            $pivotData = $tanques->mapWithKeys(function ($tanque) {
                return [
                    $tanque['id'] => ['capacidad' => $tanque['capacidad']]
                ];
            })->toArray();

            $estacion->equipos()->sync($id_equipos); 
           
            $estacion->tanques()->sync($pivotData);

    
            return redirect()->route('dictamen_datos.create')->with('success', 'Estacion registrada correctamente');

        }else{
            return redirect()->route('dictamen_datos.create')->with('error', 'Estacion registrada nuevamente');
        }
      
    
    }
}
