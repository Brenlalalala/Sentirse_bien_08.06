<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('turnos', function (Blueprint $table) {
            //  la columna cliente_id eliminamos
            if (Schema::hasColumn('turnos', 'cliente_id')) {
                $table->dropColumn('cliente_id');
            }

            // Luego agregamos user_id como clave foránea a users
            $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
        });
    }

        public function down(): void
    {
        Schema::table('turnos', function (Blueprint $table) {
            if (Schema::hasColumn('turnos', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }

            // Evitá restaurar cliente_id porque la tabla clientes ya no existe
            // $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
        });
    }

};
