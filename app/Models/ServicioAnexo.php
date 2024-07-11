<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioAnexo extends Model
{
    use HasFactory;

    protected $connection = 'segunda_db';
    protected $table = 'servicio_anexo_30';

    // Relación con el modelo User (uno a muchos inversa)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }

    // Relación con la cotización (uno a uno)
    public function cotizacion()
    {
        return $this->hasOne(Cotizacion_Servicio_Anexo30::class, 'servicio_anexo_id');
    }
    // Definir la relación con el modelo Estacion a través de la tabla pivote estacion_servicio
    public function estacionServicios()
    {
        return $this->belongsToMany(Estacion::class, 'estacion_servicio', 'servicio_anexo_id', 'estacion_id');
    }
}