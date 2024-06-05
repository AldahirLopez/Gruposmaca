<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialFormato extends Model
{
    use HasFactory;

    protected $connection = 'segunda_db'; // Especificar la conexión de base de datos
    protected $table = 'historial_formatos';

    protected $fillable = [
        'formato_id',
        'nombre',
        'rutadoc',
    ];
}
