<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DictamenOp extends Model
{
    use HasFactory;

    protected $connection = 'segunda_db';

    protected $table = 'numdicop';

    protected $fillable = [
        'nombre',
        // Aquí puedes agregar otros campos que desees permitir en asignación masiva
    ];

    // Relación con el modelo User
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}