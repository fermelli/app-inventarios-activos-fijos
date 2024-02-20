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
}
