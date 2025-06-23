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
        Schema::table('horarios_profesionales', function (Blueprint $table) {
            // Primero elimina la foreign key si existe
            $table->dropForeign(['servicio_id']);
            // Luego elimina la columna
            $table->dropColumn('servicio_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * En el método down, si necesitas revertir esto (volver a añadir la columna),
     * deberías añadirla. Sin embargo, para esta lógica, realmente no querrás
     * volver a tenerla. Si quisieras, sería así:
     */
    public function down(): void
    {
        Schema::table('horarios_profesionales', function (Blueprint $table) {
            // Para revertir, se debería añadir de nuevo la columna.
            // Sin embargo, si estás siguiendo la nueva lógica, NO deberías
            // querer revertir esto. La dejo comentada como referencia.
            // $table->foreignId('servicio_id')->constrained('servicios')->onDelete('cascade');
        });
    }
};