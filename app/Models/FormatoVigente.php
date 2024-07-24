<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormatoVigente extends Model
{
    use HasFactory;

    protected $connection = 'segunda_db'; // Especificar la conexión de base de datos
    protected $table = 'formatos_vigentes';

    protected $fillable = [
        'nombre',
        'rutadoc',
        'tipo_doc',
    ];
}
