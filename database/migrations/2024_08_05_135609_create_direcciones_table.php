<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('segunda_db')->create('direcciones', function (Blueprint $table) {
            $table->id(); // Esto crea una columna 'id' con tipo 'unsignedBigInteger'
            $table->string('calle')->nullable();
            $table->string('numero')->nullable();
            $table->string('numero_interior')->nullable();
            $table->string('codigo_postal')->nullable();
            $table->string('colonia')->nullable();
            $table->string('municipio')->nullable();
            $table->string('entidad_federativa')->nullable();
            $table->string('tipo'); // 'fiscal' o 'servicio'
            $table->timestamps();

            // La clave foránea se añadirá en la tabla 'estacion'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('segunda_db')->dropIfExists('direcciones');
    }
};
