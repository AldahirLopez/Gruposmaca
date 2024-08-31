<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Direccion extends Model
{
    protected $connection = 'segunda_db';  // Conexión a la segunda base de datos
    protected $table = 'direcciones';  // Nombre de la tabla

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'tipo_direccion',
        'calle',
        'numero_ext',
        'numero_int',
        'colonia',
        'codigo_postal',
        'localidad_id',
        'municipio_id',
        'entidad_federativa_id'
    ];
}
