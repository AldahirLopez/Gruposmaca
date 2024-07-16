<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion_Operacion extends Model
{
    use HasFactory;

    protected $connection = 'segunda_db';  // Conexión a la segunda base de datos
    protected $table = 'cotizacion_operacion';  // Nombre de la tabla
    
    public function servicio()
    {
        return $this->belongsTo(ServicioAnexo::class, 'servicio_id');
    }

    // Accesor para la URL completa del PDF
    public function getRutaDocUrlAttribute()
    {
        // Devuelve la URL completa basada en la ruta almacenada en la base de datos
        return url('storage/' . $this->rutadoc_cotizacion);
    }

}
