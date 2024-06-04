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
        Schema::connection('segunda_db')->create('servicio_anexo', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->string('nomenclatura')->unique()->nullable(); // Nomenclatura única, inicialmente null
            $table->string('nombre_estacion'); // Nombre de la estación
            $table->string('direccion_estacion'); // Dirección de la estación
            $table->string('estado_estacion'); // Dirección de la estación
            $table->boolean('estado')->nullable();; // Aprobacion de la estacion
            $table->boolean('pending_apro_estacion')->nullable();; // Aprobacion de la estacion
            $table->boolean('pending_deletion')->nullable();; // Pendiente de Eliminacion
            $table->boolean('eliminated_at')->nullable();; // Fecha de eliminacion
            $table->unsignedBigInteger('usuario_id'); // Relación con usuario
            
            $table->timestamps(); // Timestamps

            // Agregar la clave foránea
            $table->foreign('usuario_id')->references('id')->on('gruposmaca.users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('segunda_db')->dropIfExists('servicio_anexo');
    }
};
