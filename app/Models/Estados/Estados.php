<?php


namespace App\Models\Estados;

use Illuminate\Database\Eloquent\Model;

class Estados extends Model
{
    // Define the table if it's not the plural form of the model name
    protected $connection = 'segunda_db';  // Conexión a la segunda base de datos
    protected $table = 'states';

    // Define the primary key if it's not the default 'id'
    protected $primaryKey = 'id';

    // Define any other properties or relationships as needed
    // Relación con Municipios
    public function municipios()
    {
        return $this->hasMany(Municipios::class, 'id_state');
    }
}
