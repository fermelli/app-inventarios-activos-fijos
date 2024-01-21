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
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('categoria_padre_id')->nullable();
            $table->string('nombre', 100)->unique();
            $table->timestamp('eliminado_en')->nullable();

            $table->foreign('categoria_padre_id')
                ->references('id')->on('categorias')
                ->onDelete('no action')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropForeign(['categoria_padre_id']);
        });

        Schema::dropIfExists('categorias');
    }
};
