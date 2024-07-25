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
        Schema::connection('segunda_db')->create('equipo_estacion', function (Blueprint $table) {
            $table->unsignedBigInteger('id_estacion')->nullable();;
            $table->string('id_equipo')->nullable();
            
            $table->timestamps();

            $table->foreign('id_estacion')->references('id')->on('armonia.estacion')->onUpdate('cascade')->onDelete('SET NULL');
            $table->foreign('id_equipo')->references('num_serie')->on('armonia.equipo')->onUpdate('cascade')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipo_estacion');
    }
};
