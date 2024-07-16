<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dictamen_Diseño extends Model
{
    use HasFactory;

    protected $connection = 'segunda_db';  // Conexión a la segunda base de datos
    protected $table = 'dictamenes_diseño';  // Nombre de la tabla

    // Definición de la relación con el modelo ServicioAnexo
    protected $fillable = [
        'nomenclatura',
        'fecha_emision',
        'fecha_inicio',
        'rutadoc_diseño',
        'rutadoc_sustento_diseño',
    ];

    public function estacion()
    {
        return $this->belongsTo(Estacion::class, 'estacion_id');
    }
}