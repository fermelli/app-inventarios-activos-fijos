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
            CREATE VIEW cantidad_activos_fijos_por_estado_agrupados_por_categoria AS
            SELECT c.nombre AS categoria,
            (
                SELECT COUNT(a.id) FROM articulos AS a
                WHERE a.tipo = "activo fijo"
                    AND a.estado_activo_fijo = "activo"
                    AND a.categoria_id = c.id
            ) AS activo,
            (
                SELECT COUNT(a.id) FROM articulos AS a
                WHERE a.tipo = "activo fijo"
                    AND a.estado_activo_fijo = "de baja"
                    AND a.categoria_id = c.id
            ) AS "de baja",
            (
                SELECT COUNT(a.id) FROM articulos AS a
                WHERE a.tipo = "activo fijo"
                    AND a.estado_activo_fijo = "en mantenimiento"
                    AND a.categoria_id = c.id
            ) AS "en mantenimiento",
            (
                SELECT COUNT(a.id) FROM articulos AS a
                WHERE a.tipo = "activo fijo"
                    AND a.categoria_id = c.id
            ) AS "total"
            FROM articulos AS a
            INNER JOIN categorias AS c
            ON a.categoria_id = c.id
            WHERE a.tipo = "activo fijo"
            GROUP BY c.nombre;
        ');

        DB::statement('
            CREATE VIEW usuarios_con_mas_activos_fijos_asignados AS
            SELECT u.nombre AS usuario, COUNT(u.nombre) AS cantidad
            FROM usuarios AS u
            INNER JOIN asignaciones_activos_fijos AS aaf
            ON u.id = aaf.asignado_a_id
            WHERE aaf.fecha_devolucion IS NULL
            GROUP BY u.nombre
            ORDER BY cantidad DESC
            LIMIT 10;
        ');

        DB::statement('
            CREATE VIEW ubicaciones_con_mas_activos_fijos_asignados AS
            SELECT u.nombre AS ubicacion, COUNT(u.nombre) AS cantidad
            FROM ubicaciones AS u
            INNER JOIN asignaciones_activos_fijos AS aaf
            ON u.id = aaf.ubicacion_id
            WHERE aaf.fecha_devolucion IS NULL
            GROUP BY u.nombre
            ORDER BY cantidad DESC
            LIMIT 10;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW cantidad_activos_fijos_por_estado_agrupados_por_categoria');
        DB::statement('DROP VIEW usuarios_con_mas_activos_fijos_asignados');
        DB::statement('DROP VIEW ubicaciones_con_mas_activos_fijos_asignados');
    }
};
