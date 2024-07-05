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
        Schema::connection('segunda_db')->create('servicio_anexo_30', function (Blueprint $table) {
            $table->id(); // ID autoincremental
            $table->string('nomenclatura')->unique()->nullable(); // Nomenclatura única, inicialmente null
            $table->boolean('pending_apro_servicio')->nullable();// Aprobacion de la estacion
            $table->boolean('pending_deletion_servicio')->nullable(); // Pendiente de Eliminacion
            $table->dateTime('date_eliminated_at')->nullable();// Fecha de eliminacion
            $table->dateTime('date_recepcion_at')->nullable();// Fecha de eliminacion
            $table->dateTime('date_inspeccion_at')->nullable();// Fecha de eliminacion
            
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
        Schema::connection('segunda_db')->dropIfExists('servicio_anexo_30');
    }
};
