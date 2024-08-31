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
    // Definir la relación con el modelo ServicioAnexo a través de la tabla pivote estacion_servicio
    public function servicioAnexo()
    {
        return $this->belongsToMany(ServicioAnexo::class, 'estacion_servicio', 'estacion_id', 'servicio_anexo_id');
    }

    // Relación con la tabla pivote estacion_servicio
    public function estacionServicios()
    {
        return $this->hasMany(Estacion_Servicio::class, 'estacion_id');
    }

    public function estacionServicioOperacionMantenimiento()
    {
        return $this->hasMany(Estacion_Operacion::class,'estacion_id');
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'usuario_estacion', 'estacion_id', 'usuario_id');
    }

     //Relaciones N a N
     public function equipos()
     {
         return $this->belongsToMany(Equipo::class, 'equipo_estacion', 'id_estacion', 'id_equipo');
     }

     public function tanques()
     {
         return $this->belongsToMany(Tanque::class, 'estacion_tanque', 'id_estacion', 'id_tanque')
         ->withPivot(['capacidad']);;
     }


}
