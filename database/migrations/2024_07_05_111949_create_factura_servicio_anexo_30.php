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
        Schema::connection('segunda_db')->create('factura_servicio_anexo_30', function (Blueprint $table) {
            $table->id();
            $table->string('ruta_pdf');
            $table->string('rutad_xml');
            $table->unsignedBigInteger('id_pago');
            $table->unsignedBigInteger('usuario_id');
            $table->timestamps();

            // Agregar la clave foránea
            $table->foreign('id_pago')->references('id')->on('armonia.pago_servicio_anexo_30');
           
            // Agregar la clave foránea
            $table->foreign('usuario_id')->references('id')->on('gruposmaca.users');
           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factura_servicio_anexo_30');
    }
};
