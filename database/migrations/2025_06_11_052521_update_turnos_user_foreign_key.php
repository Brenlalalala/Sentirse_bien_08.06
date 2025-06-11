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
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            // Restaurar cliente_id si hacés rollback
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
        });
    }
};
