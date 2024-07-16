<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura_Operacion extends Model
{
    use HasFactory;

    protected $connection = 'segunda_db';  // Conexión a la segunda base de datos
    protected $table = 'factura_operacion';  // Nombre de la tabla
    
    protected $fillable = [

        'rutadoc_factura',
        'id_pago',
    ];

    public function pago()
    {
        return $this->belongsTo(Pago_operacion::class, 'id_pago');
    }

}
