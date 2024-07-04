<?php
namespace App\Http\Controllers;

use App\Models\EstacionServicio;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Models\Cotizacion_Servicio_Anexo30;
use App\Models\ServicioAnexo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;


use Illuminate\Support\Facades\Auth; // Importa la clase Auth

class EstacionesDeServicioController extends Controller
{
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

        $estaciones = EstacionServicio::all();
        return view('armonia.estaciones_de_servicio.index', compact('usuario', 'estados','estaciones'));
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $data = $request->validate([
            'id_usuario' => 'required',
            'razonsocial' => 'required|string|max:255',
            'rfc' => 'required|string|max:255',
            'domicilio_fiscal' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255',
            'cre' => 'required|string|max:255',
            'constancia' => 'nullable|string|max:255',
            'domicilio_estacion' => 'required|string|max:255',
            'estado' => 'required|string|max:255',
            'contacto' => 'required|string|max:255',
            'nom_repre' => 'required|string|max:255',
            'fecha_recepcion' => 'nullable|date',
            'fecha_inspeccion' => 'nullable|date',
            'servicio_anexo_id' => 'nullable|exists:armonia.servicio_anexo_30,id', // Validar que el servicio existe
            'servicio_operacion_id' => 'nullable|exists:operacion.servicio,id',
            'servicio_diseño_id' => 'nullable|exists:diseno.servicio,id',
            'servicio_construccion_id' => 'nullable|exists:construccion.servicio,id'
        ]);

        // Crear una nueva instancia del modelo EstacionServicio
        $estacionServicio = new EstacionServicio();

        // Asignar los datos validados al modelo
        $estacionServicio->Num_Estacion = $data['id_usuario'];
        $estacionServicio->Razon_Social = $data['razonsocial'];
        $estacionServicio->RFC = $data['rfc'];
        $estacionServicio->Domicilio_Fiscal = $data['domicilio_fiscal'];
        $estacionServicio->Telefono = $data['telefono'];
        $estacionServicio->Correo = $data['correo'];
        $estacionServicio->Num_CRE = $data['cre'];
        $estacionServicio->Num_Constancia = $data['constancia'];
        $estacionServicio->Domicilio_Estacion_Servicio = $data['domicilio_estacion'];
        $estacionServicio->Estado_Republica_Estacion = $data['estado'];
        $estacionServicio->Contacto = $data['contacto'];
        $estacionServicio->Nombre_Representante_Legal = $data['nom_repre'];
        $estacionServicio->Fecha_Recepcion_Solicitud = $data['fecha_recepcion'] ?? null; // Nullable date
        $estacionServicio->Fecha_Inspeccion = $data['fecha_inspeccion'] ?? null; // Nullable date
        $estacionServicio->usuario_id = $data['id_usuario'];
        $estacionServicio->servicio_anexo_id = $data['servicio_anexo_id'] ?? null; // Relación con servicio anexo
        $estacionServicio->servicio_operacion_id = $data['servicio_operacion_id'] ?? null;
        $estacionServicio->servicio_diseño_id = $data['servicio_diseño_id'] ?? null;
        $estacionServicio->servicio_construccion_id = $data['servicio_construccion_id'] ?? null;

        // Guardar el objeto en la base de datos
        $estacionServicio->save();

        return redirect()->route('estaciones.index')->with('success', 'estacion agrega exitosamente');
    }


}