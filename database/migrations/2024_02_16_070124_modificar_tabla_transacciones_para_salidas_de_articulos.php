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
        Schema::table('transacciones', function (Blueprint $table) {
            $table->enum('estado_solicitud', ['pendiente', 'aprobada', 'rechazada', 'entregada', 'anulada'])
                ->nullable()->change();
            $table->unsignedBigInteger('anulador_id')->nullable()->after('numero_solicitud');
            $table->unsignedBigInteger('despachante_id')->nullable()->after('numero_solicitud');
            $table->timestamp('fecha_hora_anulacion')->nullable()->after('numero_solicitud');
            $table->timestamp('fecha_hora_entrega')->nullable()->after('numero_solicitud');
            $table->timestamp('fecha_hora_atencion')->nullable()->after('numero_solicitud');

            $table->foreign('despachante_id')
                ->references('id')->on('usuarios')
                ->onDelete('no action')
                ->onUpdate('cascade');
            $table->foreign('anulador_id')
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
        Schema::table('transacciones', function (Blueprint $table) {
            $table->dropForeign(['despachante_id']);
            $table->dropForeign(['anulador_id']);
            $table->enum('estado_solicitud', ['pendiente'])->nullable()->change();
            $table->dropColumn('fecha_hora_atencion');
            $table->dropColumn('fecha_hora_entrega');
            $table->dropColumn('fecha_hora_anulacion');
            $table->dropColumn('despachante_id');
            $table->dropColumn('anulador_id');
        });
    }
};
