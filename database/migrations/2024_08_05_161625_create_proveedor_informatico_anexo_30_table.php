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
        Schema::connection('segunda_db')->create('proveedor_informatico_anexo_30', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('rfc');
            $table->unsignedBigInteger('servicio_anexo_id');
            $table->string('nombre_software');
            $table->string('version');

            $table->foreign('servicio_anexo_id')
                ->references('id')->on('armonia.servicio_anexo_30')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('segunda_db')->dropIfExists('proveedor_informatico_anexo_30');
    }
};
