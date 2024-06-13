<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Datos_Servicio extends Model
{
    use HasFactory;

    protected $connection = 'segunda_db';
    protected $table = 'datos_servicio_anexo_30';

    protected $fillable = [
        'Razon_Social',
        'RFC',
        'Domicilio_Fiscal',
        'Telefono',
        'Correo',
        'Fecha_Recepcion_Solicitud',
        'Num_CRE',
        'Num_Constancia',
        'Domicilio_Estacion_Servicio',
        'Direccion_Estado',
        'Contacto',
        'Nombre_Representante_Legal',
        'Fecha_Inspeccion',
        'servicio_anexo_id',
    ];
}