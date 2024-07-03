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
        Schema::connection('segunda_db')->create('estaciones_de_servicio', function (Blueprint $table) {
            $table->id();
            $table->string('Razon_Social');
            $table->string('RFC');
            $table->string('Domicilio_Fiscal');
            $table->string('Telefono');
            $table->string('Correo');
            $table->string('Num_CRE');
            $table->string('Num_Constancia')->nullable();
            $table->string('Domicilio_Estacion_Servicio');
            $table->string('Estado_Republica_Estacion');
            $table->string('Contacto');
            $table->string('Nombre_Representante_Legal');
            $table->unsignedBigInteger('usuario_id');
            $table->timestamps();

             // Agregar la clave foránea
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
