<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Control_Volumetrico extends Model
{
    use HasFactory;


    protected $connection = 'segunda_db';

    // Especificar la tabla asociada al modelo
    protected $table = 'control_volumetrico';

    protected $fillable = [
        'nombre',
        'version ',
    ];
    


}
