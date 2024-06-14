<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion_Servicio_Anexo30 extends Model
{
    use HasFactory;

    protected $connection = 'segunda_db';
    protected $table = 'cotizacion_anexo_30';
    public function servicio()
    {
        return $this->belongsTo(ServicioAnexo::class, 'servicio_id');
    }
    // Accesor para la URL completa del PDF
    public function getRutaDocUrlAttribute()
    {
        // Asegúrate de ajustar la ruta base según sea necesario
        return url($this->rutadoc_cotizacion);
    }
}