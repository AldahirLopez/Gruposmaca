<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago_Operacion extends Model
{
    use HasFactory;
    
    protected $connection = 'segunda_db';  // Conexión a la segunda base de datos
    protected $table = 'pago_operacion';  // Nombre de la tabla
    
    protected $fillable = [
        'rutadoc_pago',
        'servicio_id ',
    ];

    public function servicio()
    {
        return $this->belongsTo(ServicioOperacion::class, 'servicio_id');
    }
}
