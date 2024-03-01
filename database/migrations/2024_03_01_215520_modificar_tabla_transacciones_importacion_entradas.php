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
        Schema::table('transacciones', function (Blueprint $table) {
            $table->unsignedBigInteger('institucion_id')->nullable()->change();
            $table->enum('comprobante', ['factura', 'recibo', 'nota', 'boleta', 'otro', 'importacion datos'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::table('transacciones', function (Blueprint $table) {
            $table->unsignedBigInteger('institucion_id')->nullable(false)->change();
            $table->enum('comprobante', ['factura', 'recibo', 'nota', 'boleta', 'otro'])->change();
        });

        Schema::enableForeignKeyConstraints();
    }
};
