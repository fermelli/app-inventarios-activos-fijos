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
        Schema::table('transacciones', function (Blueprint $table) {
            $table->unsignedBigInteger('usuario_id')->nullable()->change();
            $table->unsignedBigInteger('institucion_id')->nullable()->change();
            $table->date('fecha')->nullable()->change();
            $table->enum('tipo', ['entrada', 'solicitud'])->change();
            $table->enum('comprobante', Transaccion::COMPROBANTES)->nullable()->change();
            $table->string('numero_comprobante', 100)->nullable()->change();
            $table->unsignedBigInteger('solicitante_id')->nullable()->after('institucion_id');
            $table->unsignedInteger('numero_solicitud')->nullable()->after('numero_comprobante');

            $table->foreign('solicitante_id')
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
        Schema::disableForeignKeyConstraints();

        Schema::table('transacciones', function (Blueprint $table) {
            $table->dropForeign(['solicitante_id']);
            $table->dropColumn('solicitante_id');
            $table->dropColumn('numero_solicitud');

            $table->unsignedBigInteger('usuario_id')->nullable(false)->change();
            $table->unsignedBigInteger('institucion_id')->nullable(false)->change();
            $table->date('fecha')->nullable(false)->change();
            $table->enum('tipo', ['entrada'])->change();
            $table->enum('comprobante', Transaccion::COMPROBANTES)->nullable(false)->change();
            $table->string('numero_comprobante', 100)->nullable(false)->change();
        });

        Schema::enableForeignKeyConstraints();
    }
};
