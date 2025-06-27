<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE pagos MODIFY forma_pago ENUM('efectivo', 'debito', 'credito') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE pagos MODIFY forma_pago ENUM('debito', 'otro') NOT NULL");
    }
};
