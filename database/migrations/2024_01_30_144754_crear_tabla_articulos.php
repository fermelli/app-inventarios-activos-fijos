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
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categoria_id');
            $table->unsignedBigInteger('unidad_id');
            $table->unsignedBigInteger('ubicacion_id');
            $table->string('codigo', 100)->unique();
            $table->string('nombre', 255)->unique();
            $table->timestamp('creado_en');
            $table->timestamp('actualizado_en');
            $table->timestamp('eliminado_en')->nullable();

            $table->foreign('categoria_id')
                ->references('id')->on('categorias')
                ->onDelete('no action')
                ->onUpdate('cascade');
            $table->foreign('unidad_id')
                ->references('id')->on('unidades')
                ->onDelete('no action')
                ->onUpdate('cascade');
            $table->foreign('ubicacion_id')
                ->references('id')->on('ubicaciones')
                ->onDelete('no action')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articulos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropForeign(['unidad_id']);
            $table->dropForeign(['ubicacion_id']);
        });
        
        Schema::dropIfExists('articulos');
    }
};
