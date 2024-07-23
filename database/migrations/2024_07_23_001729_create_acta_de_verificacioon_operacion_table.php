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
        Schema::connection('segunda_db')->create('acta_de_verificacion_operacion', function (Blueprint $table) {
            $table->id();
            $table->string('rutadoc_acta');
            $table->unsignedBigInteger('servicio_id');
            $table->timestamps();

            // Referencia con eliminación en cascada
            $table->foreign('servicio_id')
                ->references('id')->on('armonia.operacion_mantenimiento')
                ->onDelete('cascade'); // Esto permite eliminar en cascada
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acta_de_verificacioon_operacion');
    }
};
