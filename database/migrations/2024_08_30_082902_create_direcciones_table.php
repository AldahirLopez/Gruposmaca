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
        Schema::connection('segunda_db')->create('direcciones', function (Blueprint $table) {
            $table->id();
            $table->string('tipo_direccion', 50)->nullable();
            $table->string('calle', 255)->nullable();
            $table->string('numero_ext', 10)->nullable();
            $table->string('numero_int', 10)->nullable();
            $table->string('colonia', 255)->nullable();
            $table->integer('codigo_postal')->nullable();
            $table->string('localidad_id', 50)->nullable();
            $table->integer('municipio_id')->nullable();
            $table->integer('entidad_federativa_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('direcciones');
    }
};
