<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DictamenOp extends Model
{
    use HasFactory;

    protected $connection = 'segunda_db';

    protected $table = 'numdicop';
    // Relación con el modelo User
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}