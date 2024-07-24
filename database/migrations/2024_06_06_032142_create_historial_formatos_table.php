<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialFormatosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('segunda_db')->create('historial_formatos', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_doc');
            $table->string('nombre');
            $table->string('rutadoc');
            $table->unsignedBigInteger('formato_id');
            $table->timestamps();

            // Definir la clave foránea con la tabla de formatos vigentes
            $table->foreign('formato_id')->references('id')->on('armonia.formatos_vigentes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_formatos');
    }
}
