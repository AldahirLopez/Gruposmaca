<?php

namespace App\Http\Controllers;

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

class ServicioAnexo30PruebaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $connection = 'segunda_db';

    function __construct()
    {
        $this->middleware('permission:ver-servicio|crear-servicio|editar-servicio|borrar-servicio', ['only' => ['index']]);
        $this->middleware('permission:crear-servicio', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-servicio', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-servicio', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        // Inicializar colecciones y variables necesarias
        $usuarios = collect();
        $servicios = collect();
        $warnings = []; 

        // Obtener el rol "Verificador Anexo 30"
        $rolVerificador = Role::on('mysql')->where('name', 'Verificador Anexo 30')->first();

        // Verificar si el rol existe y obtener los usuarios asociados
        if ($rolVerificador) {
            // Obtener los IDs de los usuarios que tienen el rol "Verificador Anexo 30"
            $usuariosConRol = $rolVerificador->users()->pluck('id');

            // Si hay usuarios con el rol, obtenerlos
            if ($usuariosConRol->isNotEmpty()) {
                $usuarios = User::on('mysql')->whereIn('id', $usuariosConRol)->get();
            }
        }

        // Verificar si el usuario está autenticado
        $usuario = Auth::user();

        if ($usuario) {
            // Verificar si se envió un usuario seleccionado en la solicitud
            $usuarioSeleccionado = $request->input('usuario_id');

            // Si se seleccionó un usuario, filtrar los servicios por ese usuario, de lo contrario, obtener todos los servicios
            if ($usuarioSeleccionado) {
                $servicios = ServicioAnexo::where('usuario_id', $usuarioSeleccionado)->get();
            } else {
                // Verificar si el usuario es administrador
                if ($usuario->hasAnyRole(['Administrador', 'Auditor'])) {
                    // Si es administrador, obtener todos los servicios
                    $servicios = ServicioAnexo::all();
                } else {
                    // Si no es administrador, obtener solo los servicios del usuario autenticado
                    $servicios = ServicioAnexo::where('usuario_id', $usuario->id)->get();
                }
            }
        }

        // Siempre retornar la vista, incluso si no se encuentran usuarios o servicios
        return view('armonia.anexo.servicio_anexo.index', compact('servicios', 'usuarios'));
    }


   

    
    /**
     * Show the form for creating a new resource.
     */
    

    

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
    

    

    

    


    


    


}
