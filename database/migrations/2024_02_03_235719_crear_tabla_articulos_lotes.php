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
        Schema::create('articulos_lotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('articulo_id');
            $table->unsignedBigInteger('detalle_transaccion_id');
            $table->string('lote', 100)->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->unsignedDecimal('cantidad', 10, 2);

            $table->foreign('articulo_id')
                ->references('id')->on('articulos')
                ->onDelete('no action')
                ->onUpdate('cascade');
            $table->foreign('detalle_transaccion_id')
                ->references('id')->on('detalles_transacciones')
                ->onDelete('no action')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articulos_lotes', function (Blueprint $table) {
            $table->dropForeign(['articulo_id']);
            $table->dropForeign(['detalle_transaccion_id']);
        });
        
        Schema::dropIfExists('articulos_lotes');
    }
};
