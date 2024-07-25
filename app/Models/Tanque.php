<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tanque extends Model
{
    use HasFactory;
    protected $connection = 'segunda_db';
    
    protected $table = 'tanque';
    
    // Especificar la tabla asociada al modelo

    protected $fillable = [
        'nombre',
    ];
}

