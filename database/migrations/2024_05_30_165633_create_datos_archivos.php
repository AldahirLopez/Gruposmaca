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
        Schema::connection('segunda_db')->create('datos_archivos', function (Blueprint $table) {
            $table->id();
            $table->string('Razon_Social');
            $table->string('RFC');
            $table->string('Domicilio_Fiscal');
            $table->string('Telefono');
            $table->string('Correo');
            $table->string('Fecha_Recepcion_Solicitud');
            $table->string('Num_CRE');
            $table->string('Num_Constancia')->nullable();
            $table->string('Domicilio_Estacion_Servicio');
            $table->string('Contacto');
            $table->string('Nombre_Representante_Legal');
            $table->string('Fecha_Inspeccion');
            $table->unsignedBigInteger('servicio_anexo_id');
            $table->timestamps();

            //Referencia al numero de dictamen 1 dictamen puede tener varios archivos 
            $table->foreign('servicio_anexo_id')->references('id')->on('armonia.servicio_anexo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_archivos');
    }
};
