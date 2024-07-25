<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    protected $connection = 'segunda_db';
    protected $table = 'equipo';
    
    protected $primaryKey = 'num_serie';
    protected $keyType = 'string';
    // Especificar la tabla asociada al modelo
   

    protected $fillable = [
        'num_serie',
        'modelo',
        'marca',
        'tipo',
    ];
}
