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
        Schema::connection('segunda_db')->create('usuario_estacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('estacion_id');
            $table->timestamps();

            //Relacion Tabla usuario
            $table->foreign('usuario_id')->references('id')->on('gruposmaca.users');

            //Relacion tabla estacion
            $table->foreign('estacion_id')->references('id')->on('armonia.estacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario_estacion');
    }
};
