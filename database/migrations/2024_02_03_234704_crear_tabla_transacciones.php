<?php

use App\Models\Transaccion;
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
        Schema::create('transacciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('institucion_id');
            $table->date('fecha');
            $table->enum('tipo', ['entrada']);
            $table->enum('comprobante', Transaccion::COMPROBANTES);
            $table->string('numero_comprobante', 100);
            $table->string('observacion', 255)->nullable();
            $table->timestamp('creado_en');
            $table->timestamp('actualizado_en');
            $table->timestamp('eliminado_en')->nullable();

            $table->foreign('usuario_id')
                ->references('id')->on('usuarios')
                ->onDelete('no action')
                ->onUpdate('cascade');
            $table->foreign('institucion_id')
                ->references('id')->on('instituciones')
                ->onDelete('no action')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transacciones', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
            $table->dropForeign(['institucion_id']);
        });
        
        Schema::dropIfExists('transacciones');
    }
};
