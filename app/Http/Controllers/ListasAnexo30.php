<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ListasAnexo30 extends Controller
{
    public function loadForm($type)
    {
        switch ($type) {
            case 'estacion':
                return view('armonia.servicio_anexo_30.datos_servicio_anexo.forms_listas.lista_inspeccion_estacion');
            case 'transporte':
                return view('armonia.servicio_anexo_30.datos_servicio_anexo.forms_listas.lista_inspeccion_transporte');
            case 'almacenamiento':
                return view('armonia.servicio_anexo_30.datos_servicio_anexo.forms_listas.lista_inspeccion_almacenamiento');
            default:
                abort(404); // Maneja el error si el tipo no es válido
        }
    }
}
