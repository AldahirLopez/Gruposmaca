<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Estacion;
use App\Models\Estacion_Servicio;
use App\Models\Expediente_Servicio_Anexo_30;
use App\Models\ServicioAnexo;
use App\Models\User;
use App\Models\Usuario_Estacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PhpOffice\PhpWord\TemplateProcessor;
use Illuminate\Support\Facades\DB;

class Usuario_EstacionController extends Controller
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

        $UsuarioEstaciones = Usuario_Estacion::all();
        $estaciones = Estacion::all();
        $usuarios = User::all();
        return view('armonia.estacion_usuario.index', compact('usuario', 'estados', 'UsuarioEstaciones', 'estaciones', 'usuarios'));
    }

    public function AsignarEstacion(Request $request)
    {
        $id_usuario = $request->input('id_usuario');
        $id_estacion = $request->input('id_estacion');

        $existeRelacion = Usuario_Estacion::where('usuario_id', $id_usuario)
            ->where('estacion_id', $id_estacion)
            ->exists();

        if ($existeRelacion) {
            return redirect()->route('usuario_estacion.index')->with('error', 'La relación ya existe.');
        } else {
            $usuario_estacion = new Usuario_Estacion();
            $usuario_estacion->usuario_id = $id_usuario;
            $usuario_estacion->estacion_id = $id_estacion;
            $usuario_estacion->save();

            return redirect()->route('usuario_estacion.index')->with('success', 'Relación creada con éxito.');
        }
    }

    public function destroy($id)
    {
        // Buscar la relación por su ID y eliminarla
        $relacion = Usuario_Estacion::findOrFail($id);
        $relacion->delete();

        // Redirigir de vuelta a la vista index con un mensaje de éxito
        return redirect()->route('usuario_estacion.index')->with('success', 'Relación eliminada correctamente.');
    }

}