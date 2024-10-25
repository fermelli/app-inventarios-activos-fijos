<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function cantidadRegistros()
    {
        $cantidadRegistros = DB::table('cantidad_registros')->first();

        return response()->jsonResponse('Cantidad de registros', collect($cantidadRegistros), 200);
    }

    public function articulosConStockMinimo()
    {
        $articulosConStockMinimo = DB::table('articulos_con_stock_minimo')->get()->toArray();

        return response()->jsonResponse('Artículos con stock mínimo', $articulosConStockMinimo, 200);
    }

    public function articulosRecientementeVencidos()
    {
        $articulosRecientementeVencidos = DB::table('articulos_recientemente_vencidos')->get()->toArray();

        return response()->jsonResponse('Artículos recientemente vencidos', $articulosRecientementeVencidos, 200);
    }

    public function articulosProximosVencer()
    {
        $articulosProximosAVencer = DB::table('articulos_proximos_a_vencer')->get()->toArray();

        return response()->jsonResponse('Artículos próximos a vencer', $articulosProximosAVencer, 200);
    }

    public function cantidadActivosFijosPorEstadoAgrupadosPorCategoria()
    {
        $cantidadActivosFijosPorEstadoAgrupadosPorCategoria = DB::table(
            'cantidad_activos_fijos_por_estado_agrupados_por_categoria'
        )->get()->toArray();

        return response()->jsonResponse(
            'Cantidad de activos fijos por estado agrupados por categoría',
            $cantidadActivosFijosPorEstadoAgrupadosPorCategoria,
            200
        );
    }

    public function usuariosConMasActivosFijosAsignados()
    {
        $usuariosConMasActivosFijosAsignados = DB::table('usuarios_con_mas_activos_fijos_asignados')->get()->toArray();

        return response()->jsonResponse(
            'Usuarios con más activos fijos asignados',
            $usuariosConMasActivosFijosAsignados,
            200
        );
    }

    public function ubicacionesConMasActivosFijosAsignados()
    {
        $ubicacionesConMasActivosFijosAsignados = DB::table('ubicaciones_con_mas_activos_fijos_asignados')
                                                    ->get()->toArray();

        return response()->jsonResponse(
            'Ubicaciones con más activos fijos asignados',
            $ubicacionesConMasActivosFijosAsignados,
            200
        );
    }
}
