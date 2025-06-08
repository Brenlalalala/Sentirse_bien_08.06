<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('turnos', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('cliente_id'); // referencia al cliente
        $table->unsignedBigInteger('servicio_id'); // referencia al servicio
        $table->date('fecha');
        $table->time('hora');
        $table->string('estado')->default('pendiente'); // pendiente, confirmado, cancelado, etc.
        $table->timestamps();

        // claves foráneas
        $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');
    });
}

};
