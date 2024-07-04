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
        // Crear la tabla unificada
        Schema::connection('segunda_db')->create('estaciones_de_servicio', function (Blueprint $table) {
            $table->id();
            $table->string('num_estacion');
            $table->string('razon_social');
            $table->string('rfc')->nullable();
            $table->string('domicilio_fiscal')->nullable();
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->string('num_cre')->nullable();
            $table->string('num_constancia')->nullable();
            $table->string('domicilio_estacion_servicio')->nullable();
            $table->string('estado_republica_estacion');
            $table->string('contacto')->nullable();
            $table->string('nombre_representante_legal')->nullable();
            $table->dateTime('fecha_recepcion_solicitud')->nullable();
            $table->dateTime('fecha_inspeccion')->nullable();
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('gruposmaca.users');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estaciones_de_servicio');
    }
};

