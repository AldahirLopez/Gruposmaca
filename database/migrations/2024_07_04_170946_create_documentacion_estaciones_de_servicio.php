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
        Schema::connection('segunda_db')->create('documentacion_estaciones_de_servicio', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('rutadoc_estacion');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('estacion_id');
            $table->timestamps();

            // Agregar la clave foránea
            $table->foreign('usuario_id')->references('id')->on('gruposmaca.users');
            // Agregar la clave foránea
            $table->foreign('estacion_id')->references('id')->on('armonia.estaciones_de_servicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentacion_estaciones_de_servicio');
    }
};
