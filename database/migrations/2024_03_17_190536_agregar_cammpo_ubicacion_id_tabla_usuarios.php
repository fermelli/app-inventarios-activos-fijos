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
        Schema::table('usuarios', function (Blueprint $table) {
            $table->unsignedBigInteger('dependencia_id')->nullable();

            $table->foreign('dependencia_id')
                ->references('id')
                ->on('ubicaciones')
                ->onDelete('no action')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign(['dependencia_id']);
            $table->dropColumn('dependencia_id');
        });
    }
};
