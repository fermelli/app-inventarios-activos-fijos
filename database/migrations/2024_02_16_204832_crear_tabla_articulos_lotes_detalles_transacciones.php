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
        Schema::create('articulos_lotes_detalles_transacciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('detalle_transaccion_id');
            $table->unsignedBigInteger('articulo_lote_id');
            $table->unsignedDecimal('cantidad', 10, 2);

            $table->foreign('detalle_transaccion_id', 'a_l_d_t_detalle_transaccion_id_foreign')
                ->references('id')->on('detalles_transacciones')
                ->onDelete('no action')
                ->onUpdate('cascade');
            $table->foreign('articulo_lote_id', 'a_l_d_t_articulo_lote_id_foreign')
                ->references('id')->on('articulos_lotes')
                ->onDelete('no action')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articulos_lotes_detalles_transacciones');
    }
};
