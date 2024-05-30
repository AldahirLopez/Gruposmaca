<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Datos_Servicio;

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
        $archivoAnexo = Datos_Servicio::where('servicio_anexo_id', $servicio_anexo_id)->first();

        return view('armonia.anexo.servicio_anexo.archivos_anexo.index', compact('archivoAnexo', 'estados', 'servicio_anexo_id'));
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
    public function store(Request $request)
    {
        // Validar los datos
        $request->validate([

            'razon_social' => 'required|string|max:255',
            'rfc' => 'required|string|max:255',
            'domicilio_fiscal' => 'required|string|max:255',
            'telefono' => 'required|string|max:255',
            'correo' => 'required|string|email|max:255',
            'fecha_recepcion' => 'required',
            'cre' => 'required|string|max:255',
            'constancia' => 'nullable|string|max:255',
            'domicilio_estacion' => 'required|string|max:255',
            'estado' => 'required',
            'contacto' => 'required|string|max:255',
            'nom_repre' => 'required|string|max:255',
            'fecha_inspeccion' => 'required',
        ]);

        // Obtener el ID del dictamen de la URL
        $servicio_anexo_id = $request->servicio_anexo_id;

        // Crear el archivo anexo
        $archivoAnexo = new Datos_Servicio();

        // Establecer los valores de los campos
        $archivoAnexo->Razon_Social = $request->razon_social;
        $archivoAnexo->RFC = $request->rfc;
        $archivoAnexo->Domicilio_Fiscal = $request->domicilio_fiscal;
        $archivoAnexo->Telefono = $request->telefono;
        $archivoAnexo->Correo = $request->correo;
        $archivoAnexo->Fecha_Recepcion_Solicitud = $request->fecha_recepcion;
        $archivoAnexo->Num_CRE = $request->cre;
        $archivoAnexo->Num_Constancia = $request->constancia;
        $archivoAnexo->Domicilio_Estacion_Servicio = $request->domicilio_estacion;
        $archivoAnexo->Direccion_Estado = $request->estado;
        $archivoAnexo->Contacto = $request->contacto;
        $archivoAnexo->Nombre_Representante_Legal = $request->nom_repre;
        $archivoAnexo->Fecha_Inspeccion = $request->fecha_inspeccion;
        $archivoAnexo->servicio_anexo_id = $servicio_anexo_id;

        // Guardar el registro en la base de datos
        $archivoAnexo->save();

        // Redirigir con un mensaje de éxito
        return redirect()->route('armonia.anexo.servicio_anexo.archivos_anexo.index')->with('success', 'Datos Creados Exitosamente');
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
