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
        Schema::connection('segunda_db')->create('estacion', function (Blueprint $table) {
            $table->id(); // Esto crea una columna 'id' con tipo 'unsignedBigInteger'
            $table->string('num_estacion');
            $table->string('razon_social');
            $table->string('rfc');
            $table->string('estado_republica');
            $table->string('num_cre')->nullable();
            $table->string('num_constancia')->nullable();
            $table->string('telefono')->nullable();
            $table->string('correo_electronico')->nullable();
            $table->string('contacto')->nullable();
            $table->string('nombre_representante_legal')->nullable();
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->unsignedBigInteger('domicilio_fiscal_id')->nullable(); // Relacionar con la tabla de direcciones
            $table->unsignedBigInteger('domicilio_servicio_id')->nullable(); // Relacionar con la tabla de direcciones
            $table->timestamps();

            $table->foreign('domicilio_fiscal_id')->references('id')->on('direcciones')->onDelete('set null');
            $table->foreign('domicilio_servicio_id')->references('id')->on('direcciones')->onDelete('set null');
            $table->foreign('usuario_id')->references('id')->on('gruposmaca.users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('segunda_db')->dropIfExists('estacion');
    }
};
