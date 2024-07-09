<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioOperacion extends Model
{
    use HasFactory;

    protected $connection = 'segunda_db';
    protected $table = 'operacion_mantenimiento';

    // Relación con el modelo User (uno a muchos inversa)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }


}