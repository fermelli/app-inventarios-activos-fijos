<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('
            CREATE VIEW cantidad_registros AS
            SELECT
                (
                    SELECT COUNT(*) FROM articulos
                ) AS articulos,
                (
                    SELECT COUNT(*) FROM transacciones
                    WHERE tipo = "entrada" AND estado_entrada = "valida"
                ) AS entradas_validas,
                (
                    SELECT COUNT(*) FROM transacciones
                    WHERE tipo = "entrada" AND estado_entrada = "anulada"
                ) AS entradas_anuladas,
                (
                    SELECT COUNT(id) FROM transacciones
                    WHERE tipo = "solicitud" AND estado_solicitud = "pendiente"
                ) AS solicitudes_pendientes,
                (
                    SELECT COUNT(id) FROM transacciones
                    WHERE tipo = "solicitud" AND estado_solicitud = "aprobada"
                ) AS solicitudes_aprobadas,
                (
                    SELECT COUNT(id) FROM transacciones
                    WHERE tipo = "solicitud" AND estado_solicitud = "rechazada"
                ) AS solicitudes_rechazadas,
                (
                    SELECT COUNT(id) FROM transacciones
                    WHERE tipo = "solicitud" AND estado_solicitud = "entregada"
                ) AS salidas_entregadas,
                (
                    SELECT COUNT(id) FROM transacciones
                    WHERE tipo = "solicitud" AND estado_solicitud = "anulada"
                ) AS salidas_anuladas
            ;
        ');

        DB::statement('
            CREATE VIEW articulos_con_stock_minimo AS
            SELECT a.codigo, a.nombre, u.nombre AS unidad, SUM(al.cantidad) AS stock
            FROM articulos AS a
            INNER JOIN unidades AS u
            ON a.unidad_id = u.id
            INNER JOIN (
                SELECT articulo_id, cantidad
                FROM articulos_lotes
                WHERE cantidad > 0
            ) AS al
            ON a.id = al.articulo_id
            GROUP BY a.codigo, a.nombre, u.nombre
            ORDER BY stock ASC
            LIMIT 10;
        ');

        DB::statement('
            CREATE VIEW articulos_recientemente_vencidos AS
            SELECT a.codigo, a.nombre, u.nombre AS unidad, al.cantidad, al.fecha_vencimiento
            FROM (
                SELECT articulo_id, cantidad, MIN(fecha_vencimiento) AS fecha_vencimiento
                FROM articulos_lotes
                WHERE 
                    cantidad > 0
                    AND fecha_vencimiento <= CURDATE()
                    AND fecha_vencimiento > DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                GROUP BY articulo_id, cantidad
                LIMIT 10
            ) AS al
            INNER JOIN articulos AS a
            ON al.articulo_id = a.id
            INNER JOIN unidades AS u
            ON a.unidad_id = u.id;
        ');

        DB::statement('
            CREATE VIEW articulos_proximos_a_vencer AS
            SELECT a.codigo, a.nombre, u.nombre AS unidad, al.cantidad, al.fecha_vencimiento
            FROM (
                SELECT articulo_id, cantidad, MIN(fecha_vencimiento) AS fecha_vencimiento
                FROM articulos_lotes
                WHERE
                    cantidad > 0
                    AND fecha_vencimiento > CURDATE()
                    AND fecha_vencimiento <= DATE_ADD(CURDATE(), INTERVAL 90 DAY)
                GROUP BY articulo_id, cantidad
                LIMIT 10
            ) AS al
            INNER JOIN articulos AS a
            ON al.articulo_id = a.id
            INNER JOIN unidades AS u
            ON a.unidad_id = u.id;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW cantidad_registros;');
        DB::statement('DROP VIEW articulos_con_stock_minimo;');
        DB::statement('DROP VIEW articulos_recientemente_vencidos;');
        DB::statement('DROP VIEW articulos_proximos_a_vencer;');
    }
};
