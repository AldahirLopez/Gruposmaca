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
        Schema::connection('segunda_db')->create('estacion_servicio_operacion_mantenimiento', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estacion_id');
            $table->unsignedBigInteger('servicio_operacion_id');
            $table->timestamps(); // Timestamps
            $table->foreign('estacion_id', 'fk_estacion_id')
                ->references('id')
                ->on('armonia.estacion')
                ->onDelete('cascade');
            $table->foreign('servicio_operacion_id', 'fk_serv_oper_id')
                ->references('id')
                ->on('armonia.operacion_mantenimiento')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('segunda_db')->dropIfExists('estacion_servicio_operacion_mantenimiento');
    }
};
