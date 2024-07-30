<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificado_Anexo30 extends Model
{
    use HasFactory;

    protected $connection = 'segunda_db';
    protected $table = 'certificado_anexo_30';

    // Define los atributos que se pueden asignar en masa
    protected $fillable = [
        'rutadoc',
        'usuario_id',
        'servicio_anexo_id',
        // Agrega otros atributos que necesites aquí
    ];

}