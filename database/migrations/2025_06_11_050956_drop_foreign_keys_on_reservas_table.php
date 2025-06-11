<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeignKeysOnReservasTable extends Migration
{
    public function up()
    {
        // Asegúrate de eliminar las claves foráneas en la tabla `reservas`
        Schema::table('reservas', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);  // Eliminar la clave foránea que referencia a `clientes`
        });
    }

    public function down()
    {
        // Si haces rollback, necesitarás agregar la clave foránea nuevamente
        Schema::table('reservas', function (Blueprint $table) {
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });
    }
}
