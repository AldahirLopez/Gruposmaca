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
        Schema::connection('segunda_db')->create('pago_servicio_anexo_30', function (Blueprint $table) {
            $table->id();
            $table->string('comentarios');
            $table->string('rutadoc_pago');
            $table->boolean('estado_pago')->nullable();
            $table->unsignedBigInteger('servicio_anexo_id');
            $table->timestamps();

            // Agrega la clave foránea correctamente
            $table->foreign('servicio_anexo_id')
                ->references('id')->on('armonia.servicio_anexo_30')
                ->onDelete('cascade'); // Eliminación en cascada para mantener la integridad referencial
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago_servicio_anexo_30');
    }
};