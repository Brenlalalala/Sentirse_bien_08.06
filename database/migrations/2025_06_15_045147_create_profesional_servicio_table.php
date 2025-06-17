<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfesionalServicioTable extends Migration
{
    public function up()
    {
        Schema::create('profesional_servicio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');      // profesional
            $table->unsignedBigInteger('servicio_id');  // servicio
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('servicio_id')->references('id')->on('servicios')->onDelete('cascade');

            $table->unique(['user_id', 'servicio_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('profesional_servicio');
    }
}


