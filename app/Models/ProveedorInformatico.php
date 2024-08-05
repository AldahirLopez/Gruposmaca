<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProveedorInformatico extends Model
{
    use HasFactory;

    protected $connection = 'segunda_db';
    protected $table = 'proveedor_informatico_anexo_30';

    // Define los atributos que se pueden asignar en masa
    protected $fillable = [
        'nombre',
        'rfc',
        'servicio_anexo_id',
        'nombre_software',
        'version',
        // Agrega otros atributos que necesites aquí
    ];

}