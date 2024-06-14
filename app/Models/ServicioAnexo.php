<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioAnexo extends Model
{
    use HasFactory;

    protected $connection = 'segunda_db';
    protected $table = 'servicio_anexo_30';

    // Relación con el modelo User
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id', 'mysql');
    }

    public function cotizacion()
    {
        return $this->hasOne(Cotizacion_Servicio_Anexo30::class, 'servicio_anexo_id');
    }
}