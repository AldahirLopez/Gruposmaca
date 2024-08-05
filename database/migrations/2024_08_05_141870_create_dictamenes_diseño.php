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
        Schema::connection('segunda_db')->create('dictamenes_diseño', function (Blueprint $table) {
            $table->id();
            $table->string('nomenclatura')->unique()->nullable(); // Nomenclatura única, inicialmente null
            $table->string('fecha_emision');
            $table->string('fecha_inicio');
            $table->string('rutadoc_diseño');
            $table->string('rutadoc_sustento_diseño')->nullable();
            $table->unsignedBigInteger('estacion_id');
            $table->unsignedBigInteger('usuario_id');
            $table->timestamps();

            $table->foreign('estacion_id')->references('id')->on('armonia.estacion');
            $table->foreign('usuario_id')->references('id')->on('gruposmaca.users'); 

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dictamenes_diseño');
    }
};
