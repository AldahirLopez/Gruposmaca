<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago_Anexo extends Model
{
    use HasFactory;
    
    protected $connection = 'segunda_db';  // Conexión a la segunda base de datos
    protected $table = 'pago_servicio_anexo_30';  // Nombre de la tabla
    
    protected $fillable = [
        'rutadoc_pago',
        'servicio_anexo_id ',
    ];

    public function servicio()
    {
        return $this->belongsTo(ServicioAnexo::class, 'servicio_anexo_id');
    }

    public function factura()
    {
        return $this->hasOne(Factura_Operacion::class, 'id_pago');
    }
}
