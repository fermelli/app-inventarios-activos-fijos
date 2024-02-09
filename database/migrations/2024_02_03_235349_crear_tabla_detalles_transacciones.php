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
        Schema::create('detalles_transacciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaccion_id');
            $table->unsignedBigInteger('articulo_id');
            $table->unsignedDecimal('cantidad', 10, 2);

            $table->foreign('transaccion_id')
                ->references('id')->on('transacciones')
                ->onDelete('no action')
                ->onUpdate('cascade');
            $table->foreign('articulo_id')
                ->references('id')->on('articulos')
                ->onDelete('no action')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalles_transacciones', function (Blueprint $table) {
            $table->dropForeign(['transaccion_id']);
            $table->dropForeign(['articulo_id']);
        });
        
        Schema::dropIfExists('detalles_transacciones');
    }
};
