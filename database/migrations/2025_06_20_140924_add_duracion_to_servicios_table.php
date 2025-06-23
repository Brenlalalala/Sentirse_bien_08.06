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
        Schema::table('servicios', function (Blueprint $table) {
            $table->integer('duracion')->default(60)->after('precio'); // Duración en minutos
        });
    }

    public function down()
    {
        Schema::table('servicios', function (Blueprint $table) {
            $table->dropColumn('duracion');
        });
    }

};