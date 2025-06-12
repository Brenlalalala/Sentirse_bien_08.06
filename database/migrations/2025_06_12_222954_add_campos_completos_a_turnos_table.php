
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::table('turnos', function (Blueprint $table) {
        $table->foreignId('profesional_id')->nullable()->constrained('users')->onDelete('set null');
        $table->boolean('pagado')->default(false);
        $table->decimal('monto', 8, 2)->nullable();
        $table->string('medio_pago')->nullable();
        $table->string('comprobante_pdf')->nullable();
        $table->text('notas')->nullable();
    });
}

public function down()
{
    Schema::table('turnos', function (Blueprint $table) {
        $table->dropForeign(['profesional_id']);
        $table->dropColumn([
            'profesional_id',
            'pagado',
            'monto',
            'medio_pago',
            'comprobante_pdf',
            'notas',
        ]);
    });
}
};