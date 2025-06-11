<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeignKeysOnTurnosTable extends Migration
{
    public function up()
    {
        // Asegúrate de eliminar las claves foráneas en la tabla `turnos`
        Schema::table('turnos', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);  // Eliminar la clave foránea que referencia a `clientes`
        });
    }

    public function down()
    {
        // Si haces rollback, necesitarás agregar la clave foránea nuevamente
        Schema::table('turnos', function (Blueprint $table) {
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });
    }
}
