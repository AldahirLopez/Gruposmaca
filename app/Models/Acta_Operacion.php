<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acta_Operacion extends Model
{
    use HasFactory;

    protected $connection = 'segunda_db';  // Conexión a la segunda base de datos
    protected $table = 'acta_de_verificacion_operacion';  // Nombre de la tabla
    
    protected $fillable = [
        'rutadoc_acta',
        'servicio_id ',
    ];


    public function servicio()
    {
        return $this->belongsTo(ServicioOperacion::class, 'servicio_id');
    }
}
