<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('password')->after('email');
            $table->rememberToken()->after('password');
            $table->renameColumn('nombre', 'name'); // Laravel espera "name" por defecto
        });
    }

    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn(['password', 'remember_token']);
            $table->renameColumn('name', 'nombre');
        });
    }
};
