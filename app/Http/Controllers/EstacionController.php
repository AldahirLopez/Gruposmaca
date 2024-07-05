<?php
namespace App\Http\Controllers;

use App\Models\Estacion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;



use Illuminate\Support\Facades\Auth; // Importa la clase Auth

class EstacionController extends Controller
{

    protected $connection = 'segunda_db';
    public function index()
    {
        // Obtener el usuario autenticado
        $usuario = Auth::user();
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

        $estaciones = Estacion::all();
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