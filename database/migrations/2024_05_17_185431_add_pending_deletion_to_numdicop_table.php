<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPendingDeletionToNumdicopTable extends Migration
{
    public function up()
    {
        Schema::connection('segunda_db')->table('numdicop', function (Blueprint $table) {
            $table->boolean('pending_deletion')->default(false)->after('usuario_id'); // Ajusta 'nombre' según el campo después del cual quieras añadir esta columna
        });
    }

    public function down()
    {
        Schema::connection('segunda_db')->table('numdicop', function (Blueprint $table) {
            $table->dropColumn('pending_deletion');
        });
    }
}
