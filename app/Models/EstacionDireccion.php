<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstacionDireccion extends Model
{
    protected $connection = 'segunda_db';  // Conexión a la segunda base de datos
    protected $table = 'direcciones_estacion';  // Nombre de la tabla intermedia

    // Campos que se pueden llenar masivamente
    protected $fillable = [
        'id_estacion',
        'id_direccion'
    ];
}
