<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estacion extends Model
{
    use HasFactory;

    // Especificar la conexión a la base de datos secundaria
    protected $connection = 'segunda_db';

    // Especificar la tabla asociada al modelo
    protected $table = 'estacion';

    // Definir los campos que pueden ser asignados masivamente
    protected $fillable = [
        'num_estacion',
        'razon_social',
        'rfc',
        'domicilio_fiscal',
        'domicilio_estacion_servicio',
        'estado_republica_estacion',
        'num_cre',
        'num_constancia',
        'correo_electronico',
        'contacto',
        'nombre_representante_legal',
        'usuario_id',
    ];

    // Relaciones con otros modelos

    // Relación con el usuario (uno a muchos inversa)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }
}
