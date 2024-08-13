<?php

namespace App\Models\Estados;

use Illuminate\Database\Eloquent\Model;

class Postal extends Model
{
    protected $connection = 'segunda_db';  // Conexión a la segunda base de datos
    protected $table = 'suburbs';
    public function municipio()
    {
        return $this->belongsTo(Municipios::class, 'id_municipality');
    }
}
