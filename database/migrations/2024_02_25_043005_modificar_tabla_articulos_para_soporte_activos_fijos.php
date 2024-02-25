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
        Schema::table('articulos', function (Blueprint $table) {
            $table->unsignedBigInteger('unidad_id')->nullable()->change();
            $table->unsignedBigInteger('ubicacion_id')->nullable()->change();
            $table->string('observacion')->nullable()->after('nombre');
            $table->enum('estado_activo_fijo', ['activo', 'en mantenimiento', 'de baja'])->nullable()->after('nombre');
            $table->enum('tipo', ['almacenable', 'activo fijo'])->default('almacenable')->after('nombre');
            $table->text('descripcion')->nullable()->after('nombre');

            $table->dropIndex('articulos_nombre_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articulos', function (Blueprint $table) {
            $table->unsignedBigInteger('unidad_id')->change();
            $table->unsignedBigInteger('ubicacion_id')->change();
            $table->dropColumn('descripcion');
            $table->dropColumn('tipo');
            $table->dropColumn('estado_activo_fijo');
            $table->dropColumn('observacion');
        });
    }
};
