<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropClientesAndReservasTables extends Migration
{
    public function up()
    {
        Schema::dropIfExists('reservas');   // Primero eliminamos 'reservas' para evitar error por clave foránea
        Schema::dropIfExists('clientes');   // Luego eliminamos 'clientes'
    }

    public function down()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken(); // Esta línea ya agrega 'remember_token'
            $table->string('telefono')->nullable();
            $table->string('role')->default('cliente');
            $table->timestamps(); // Esto crea automáticamente created_at y updated_at
        });

        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->date('fecha');
            $table->time('hora');
            $table->string('servicio');
            $table->timestamps(); // Igual que arriba: crea created_at y updated_at automáticamente
        });
    }
}
