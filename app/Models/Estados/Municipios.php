<?php

namespace App\Models\Estados;

use Illuminate\Database\Eloquent\Model;

class Municipios extends Model
{
    protected $connection = 'segunda_db';  // Conexión a la segunda base de datos
    protected $table = 'municipalities';
    protected $primaryKey = 'id';
    public $timestamps = true; // Si usas timestamps, asegúrate de que esto esté habilitado
    protected $fillable = ['id_state', 'description', 'status']; // Si quieres asignar en masa

    // Relación con Estado
    public function estado()
    {
        return $this->belongsTo(Estados::class, 'id_state');
    }   
}
