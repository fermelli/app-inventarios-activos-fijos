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
        Schema::create('asignaciones_activos_fijos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activo_fijo_id');
            $table->unsignedBigInteger('asignado_a_id');
            $table->unsignedBigInteger('ubicacion_id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('devuelto_a_id')->nullable();
            $table->string('observacion_asignacion', 1000);
            $table->string('observacion_devolucion', 1000)->nullable();
            $table->date('fecha_asignacion');
            $table->date('fecha_devolucion')->nullable();
            $table->timestamp('creado_en');
            $table->timestamp('actualizado_en');

            $table->foreign('activo_fijo_id')
                ->references('id')->on('articulos')
                ->onDelete('no action')
                ->onUpdate('cascade');
            $table->foreign('asignado_a_id')
                ->references('id')->on('usuarios')
                ->onDelete('no action')
                ->onUpdate('cascade');
            $table->foreign('ubicacion_id')
                ->references('id')->on('ubicaciones')
                ->onDelete('no action')
                ->onUpdate('cascade');
            $table->foreign('usuario_id')
                ->references('id')->on('usuarios')
                ->onDelete('no action')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaciones_activos_fijos');
    }
};
