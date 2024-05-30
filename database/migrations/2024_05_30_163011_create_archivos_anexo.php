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
        Schema::connection('segunda_db')->create('archivos_anexo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('rutadoc');
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
        Schema::dropIfExists('archivos_anexo');
    }
};
