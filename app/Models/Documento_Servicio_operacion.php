<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento_Servicio_operacion extends Model
{
    use HasFactory;
     // Especificar la conexión a la base de datos secundaria
     protected $connection = 'segunda_db';

     // Especificar la tabla asociada al modelo
     protected $table = 'documentacion_servicio_operacion';
 
     protected $fillable = [
         'rutadoc_estacion',
         'servicio_id ',
         'usuario_id ',
     ];
}