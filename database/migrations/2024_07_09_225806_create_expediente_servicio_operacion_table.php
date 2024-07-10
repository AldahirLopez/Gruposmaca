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
        Schema::connection('segunda_db')->create('expediente_servicio_operacion', function (Blueprint $table) {

             $table->id();
            $table->string('rutadoc_expediente');
            $table->unsignedBigInteger('operacion_mantenimiento_id');
            $table->unsignedBigInteger('usuario_id');
            $table->timestamps();

            // Agregar la clave foránea
            $table->foreign('usuario_id')->references('id')->on('gruposmaca.users');
            // Agrega la clave foránea correctamente
            $table->foreign('operacion_mantenimiento_id')
                ->references('id')->on('armonia.operacion_mantenimiento')
                ->onDelete('cascade'); // Eliminación en cascada para mantener la integridad referencial
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expediente_servicio_operacion');
    }
};
