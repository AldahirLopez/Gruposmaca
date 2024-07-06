<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expediente_Servicio_Anexo_30 extends Model
{
    use HasFactory;

    // Especificar la conexión a la base de datos secundaria
    protected $connection = 'segunda_db';

    // Especificar la tabla asociada al modelo
    protected $table = 'expediente_servicio_anexo_30';

    protected $fillable = [
        'servicio_anexo_id',  // Agrega aquí los otros campos fillable si es necesario
        'rutadoc_estacion',
        'usuario_id',
        // Agrega aquí otros campos fillable si es necesario
    ];
}