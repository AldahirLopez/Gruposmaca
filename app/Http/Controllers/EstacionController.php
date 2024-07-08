<?php
namespace App\Http\Controllers;

use App\Models\Estacion;
use App\Http\Controllers\Controller;
use App\Models\Usuario_Estacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



use Illuminate\Support\Facades\Auth; // Importa la clase Auth

class EstacionController extends Controller
{

    protected $connection = 'segunda_db';
    public function index()
    {
        // Obtener el usuario autenticado
        $usuario = Auth::user();

        // Lista de estados
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

        // Inicializar la variable para almacenar las estaciones
        $estaciones = [];

        if ($usuario->hasAnyRole(['Administrador', 'Auditor'])) {
            // Mostrar todas las estaciones si el usuario es administrador o auditor
            $estaciones = Estacion::all();
        } else {
            $estacionesDirectas = Estacion::where('usuario_id', $usuario->id)->get();
            // Inicializar una colección para las estaciones relacionadas
            $estacionesRelacionadas = collect();

            // Verificar si el usuario no es administrador para buscar relaciones
            if (!$usuario->hasAnyRole(['Administrador', 'Auditor'])) {
                // Obtener las relaciones de usuario a estación
                $relaciones = Usuario_Estacion::where('usuario_id', $usuario->id)->get();

                // Recorrer las relaciones para obtener las estaciones relacionadas
                foreach ($relaciones as $relacion) {
                    // Obtener la estación relacionada y añadirla a la colección
                    $estacionRelacionada = Estacion::find($relacion->estacion_id);
                    if ($estacionRelacionada) {
                        $estacionesRelacionadas->push($estacionRelacionada);
                    }
                }
            }
            // Combinar estaciones directas y relacionadas y eliminar duplicados
            $estaciones = $estacionesDirectas->merge($estacionesRelacionadas)->unique('id');
        }

        // Pasar los datos a la vista
        return view('armonia.estacion.index', compact('usuario', 'estados', 'estaciones'));
    }
    public function store(Request $request)
    {
        // Mostrar todos los datos enviados desde el formulario
        // dd($request->all());

        try {
            // Validar los datos del formulario
            $data = $request->validate([
                'id_usuario' => 'required|integer',
                'numestacion' => 'required|string|max:255',
                'razonsocial' => 'required|string|max:255',
                'rfc' => 'required|string|max:255',
                'domicilio_fiscal' => 'required|string|max:255',
                'telefono' => 'nullable|string|max:255',
                'correo' => 'nullable|email|max:255',
                'domicilio_estacion' => 'required|string|max:255',
                'estado' => 'required|string|max:255',
            ]);

            // Mostrar los datos validados
            //dd($data);

            // Crear una nueva instancia del modelo Estacion
            $estacionServicio = new Estacion();

            // Asignar los datos validados al modelo
            $estacionServicio->num_estacion = $data['numestacion'];
            $estacionServicio->razon_social = $data['razonsocial'];
            $estacionServicio->rfc = $data['rfc'];
            $estacionServicio->domicilio_fiscal = $data['domicilio_fiscal'];
            $estacionServicio->telefono = $data['telefono'];
            $estacionServicio->correo_electronico = $data['correo'];
            $estacionServicio->domicilio_estacion_servicio = $data['domicilio_estacion'];
            $estacionServicio->estado_republica_estacion = $data['estado'];
            $estacionServicio->usuario_id = $data['id_usuario'];

            // Guardar el objeto en la base de datos
            $estacionServicio->save();

            // Redirigir con un mensaje de éxito
            return redirect()->route('estacion.index')->with('success', 'Estación agregada exitosamente');
        } catch (\Exception $e) {
            // Captura cualquier excepción y muestra el mensaje
            dd($e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Hubo un problema al intentar guardar la estación.']);
        }
    }


}