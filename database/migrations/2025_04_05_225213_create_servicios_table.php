<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios', function (Blueprint $table) {
            $table->id();
            $table->string('categoria');      // Individuales o Grupales
            $table->string('subcategoria');   // Masajes, Belleza, etc.
            $table->string('nombre');         // Anti-stress, Yoga, etc.
            $table->text('descripcion')->nullable(); // opcional
            $table->decimal('precio', 8, 2)->nullable(); // opcional
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('servicios');
    }
}