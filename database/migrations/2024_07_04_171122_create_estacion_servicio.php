<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tabla intermedia para la relación muchos a muchos con servicio_anexo_30
        Schema::connection('segunda_db')->create('estacion_servicio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estacion_id');
            $table->unsignedBigInteger('servicio_anexo_id');
            $table->timestamps(); // Timestamps
            $table->foreign('estacion_id')->references('id')->on('armonia.estacion')->onDelete('cascade');
            $table->foreign('servicio_anexo_id')->references('id')->on('armonia.servicio_anexo_30')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estacion_servicio');
    }
};
