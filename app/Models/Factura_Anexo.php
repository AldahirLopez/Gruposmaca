<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura_Anexo extends Model
{
    use HasFactory;

    protected $connection = 'segunda_db';  // Conexión a la segunda base de datos
    protected $table = ' factura_servicio_anexo_30';  // Nombre de la tabla
    
    protected $fillable = [

        'rutadoc_factura',
        'id_pago',
    ];

    public function pago()
    {
        return $this->belongsTo(Pago_Anexo::class, 'id_pago');
    }

}
