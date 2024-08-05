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
        Schema::table('armonia.estacion', function (Blueprint $table) {
         
            $table->string('sondas')->after('nombre_representante_legal');
            $table->unsignedBigInteger('id_control')->after('usuario_id')->nullable();

            $table->foreign('id_control')->references('id')->on('armonia.control_volumetrico')->onUpdate('cascade')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('estacion', function (Blueprint $table) {
            //
        });
    }
};
