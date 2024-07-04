<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstacionServicio extends Model
{
    use HasFactory;

    // Especificar la conexión a la base de datos secundaria
    protected $connection = 'segunda_db';

    // Especificar la tabla asociada al modelo
    protected $table = 'estaciones_de_servicio';

    // Definir los campos que pueden ser asignados masivamente
    protected $fillable = [
        'Num_Estacion',
        'Razon_Social',
        'RFC',
        'Domicilio_Fiscal',
        'Telefono',
        'Correo',
        'Num_CRE',
        'Num_Constancia',
        'Domicilio_Estacion_Servicio',
        'Estado_Republica_Estacion',
        'Contacto',
        'Nombre_Representante_Legal',
        'Fecha_Recepcion_Solicitud',
        'Fecha_Inspeccion',
        'usuario_id',
        'servicio_anexo_id',
        'servicio_operacion_id',
        'servicio_diseño_id',
        'servicio_construccion_id',
    ];

    // Relaciones con otros modelos

    // Relación con el usuario (uno a muchos inversa)
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id', 'id');
    }
}
