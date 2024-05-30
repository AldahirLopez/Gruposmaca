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
        Schema::connection('segunda_db')->create('numdicop', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->boolean('pending_deletion');
            $table->boolean('eliminated_at');
            $table->unsignedBigInteger('usuario_id');
            $table->timestamps();
            

            $table->foreign('usuario_id')->references('id')->on('gruposmaca.users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('numdicop');
    }
};
