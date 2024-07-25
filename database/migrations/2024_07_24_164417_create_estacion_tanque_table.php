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
        Schema::connection('segunda_db')->create('estacion_tanque', function (Blueprint $table) {

            $table->unsignedBigInteger('id_estacion')->nullable();;
            $table->unsignedBigInteger('id_tanque')->nullable();
            
            $table->float('capacidad')->nullable();
            $table->timestamps();

            $table->foreign('id_estacion')->references('id')->on('armonia.estacion')->onUpdate('cascade')->onDelete('SET NULL');
            $table->foreign('id_tanque')->references('id')->on('armonia.tanque')->onUpdate('cascade')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estacion_tanque');
    }
};
