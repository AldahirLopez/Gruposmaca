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
        Schema::connection('segunda_db')->create('factura_operacion', function (Blueprint $table) {
            $table->id();
            $table->string('observaciones')->nullable();
            $table->string('rutadoc_factura');
            $table->unsignedBigInteger('id_pago');
            $table->boolean('estado_factura')->nullable();
            $table->timestamps();

            // Referencia con eliminación en cascada
            $table->foreign('id_pago')
                ->references('id')->on('armonia.pago_operacion')
                ->onDelete('cascade'); // Esto permite eliminar en cascada
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factura_operacion');
    }
};
