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
        Schema::connection('segunda_db')->create('certificado_anexo_30', function (Blueprint $table) {
            $table->id();
            $table->string('rutadoc');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('servicio_anexo_id');
            $table->timestamps();

            // Agregar la clave foránea
            $table->foreign('usuario_id')->references('id')->on('gruposmaca.users');
            // Agregar la clave foránea
            $table->foreign('servicio_anexo_id')->references('id')->on('armonia.servicio_anexo_30')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificado_anexo_30');
    }
};
