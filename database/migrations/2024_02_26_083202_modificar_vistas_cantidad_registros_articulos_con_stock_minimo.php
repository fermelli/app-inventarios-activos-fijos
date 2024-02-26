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
        DB::statement('DROP VIEW cantidad_registros;');
        DB::statement('DROP VIEW articulos_con_stock_minimo;');

        DB::statement('
            CREATE VIEW cantidad_registros AS
            SELECT
                (
                    SELECT COUNT(id) FROM articulos WHERE tipo = "almacenable"
                ) AS articulos,
                (
                    SELECT COUNT(id) FROM articulos WHERE tipo = "activo fijo"
                ) AS activos_fijos,
                (
                    SELECT COUNT(id) FROM articulos
                    WHERE tipo = "activo fijo" AND estado_activo_fijo = "activo"
                ) AS activos_fijos_activos,
                (
                    SELECT COUNT(id) FROM articulos
                    WHERE tipo = "activo fijo" AND estado_activo_fijo = "de baja"
                ) AS activos_fijos_de_baja,
                (
                    SELECT COUNT(id) FROM transacciones
                    WHERE tipo = "entrada" AND estado_entrada = "valida"
                ) AS entradas_validas,
                (
                    SELECT COUNT(id) FROM transacciones
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
                ) AS salidas_anuladas,
                (
                    SELECT COUNT(id) FROM
                        (
                            SELECT id FROM articulos WHERE tipo = "activo fijo"
                        ) AS af RIGHT JOIN
                        (
                            SELECT activo_fijo_id FROM asignaciones_activos_fijos WHERE fecha_devolucion IS NULL
                        ) AS a ON af.id = a.activo_fijo_id
                ) AS activos_fijos_asignados,
                (
                    SELECT COUNT(id) FROM articulos
                    WHERE tipo = "activo fijo" AND id NOT IN 
                    (
                        SELECT activo_fijo_id
                        FROM asignaciones_activos_fijos
                        WHERE fecha_devolucion IS NULL
                    )
                ) AS activos_fijos_no_asignados
            ;
        ');

        DB::statement('
            CREATE VIEW articulos_con_stock_minimo AS
            SELECT a.codigo, a.nombre, u.nombre AS unidad, SUM(al.cantidad) AS stock
            FROM (
                SELECT *
                FROM articulos
                WHERE tipo = "almacenable"
            ) AS a
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW cantidad_registros;');
        DB::statement('DROP VIEW articulos_con_stock_minimo;');

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
    }
};
