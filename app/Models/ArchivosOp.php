<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArchivosOp extends Model
{
    use HasFactory;

    protected $connection = 'segunda_db';
    protected $table = 'dicarchivos';

    // Relación con el modelo DictamenOp
    public function dictamenOp()
    {
        return $this->belongsTo(DictamenOp::class, 'numdicop_id');
    }

    // Relación con el modelo User
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
