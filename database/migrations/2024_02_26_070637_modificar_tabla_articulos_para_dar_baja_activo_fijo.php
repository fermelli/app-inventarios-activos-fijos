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
            $table->dropColumn('observacion');
            $table->string('razon_baja', 1000)->nullable()->after('estado_activo_fijo');
            $table->date('fecha_baja')->nullable()->after('estado_activo_fijo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articulos', function (Blueprint $table) {
            $table->dropColumn('fecha_baja');
            $table->dropColumn('razon_baja');
            $table->string('observacion', 255)->nullable()->after('estado_activo_fijo');
        });
    }
};
