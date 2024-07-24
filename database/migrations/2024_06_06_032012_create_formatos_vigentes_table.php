<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormatosVigentesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('segunda_db')->create('formatos_vigentes', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_doc');
            $table->string('nombre');
            $table->string('rutadoc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formatos_vigentes');
    }
}
