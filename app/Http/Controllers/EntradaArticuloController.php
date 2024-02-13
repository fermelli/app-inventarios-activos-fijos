<?php

namespace App\Http\Controllers;

use App\Http\Requests\CrearEntradaArticulosRequest;
use App\Http\Requests\PaginacionConEliminadosOrdenDireccionBusquedaRequest;
use App\Models\Transaccion;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EntradaArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaginacionConEliminadosOrdenDireccionBusquedaRequest $request)
    {
        $parametros = $request->validated();
        $queryBuilder = Transaccion::with([
            'usuario',
            'institucion' => function (Builder $query) {
                $query->withTrashed();
            },
            'detallesTransacciones.articulo' => function (Builder $query) {
                $query->withTrashed();
            },
            'detallesTransacciones.articulo.unidad',
            'detallesTransacciones.articuloLote',
        ]);

        if (isset($parametros['con_eliminados'])) {
            $queryBuilder->withTrashed();
        }

        if (isset($parametros['orden_direccion'])) {
            $queryBuilder->orderBy('id', $parametros['orden_direccion']);
        }

        if (isset($parametros['busqueda'])) {
            $queryBuilder->where('numero_comprobante', 'like', "%{$parametros['busqueda']}%");
        }

        $entradasArticulos = $queryBuilder->where('tipo', Transaccion::TIPO_ENTRADA)
                                            ->paginate(
                                                $parametros['items_por_pagina'],
                                                ['*'],
                                                'pagina',
                                                $parametros['pagina']
                                            );
        
        return response()->jsonResponsePaginado('Entradas de artículos recuperadas', $entradasArticulos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CrearEntradaArticulosRequest $request)
    {
        $datos = $request->validated();

        DB::beginTransaction();

        try {
            $transaccion = Transaccion::create([
                ...$datos,
                'tipo' => Transaccion::TIPO_ENTRADA,
                'usuario_id' => $request->user()->id,
            ]);
            $datosDetallesTransacciones = $datos['detalles_transacciones'];
            
            foreach ($datosDetallesTransacciones as $datosDetalleTransaccion) {
                $detalleTransaccion = $transaccion->detallesTransacciones()->create($datosDetalleTransaccion);
                
                $datosArticuloLote = $datosDetalleTransaccion['articulo_lote'] ?? [];
                
                $detalleTransaccion->articuloLote()->create([
                    ...$datosArticuloLote,
                    'articulo_id' => $datosDetalleTransaccion['articulo_id'],
                    'cantidad' => $datosDetalleTransaccion['cantidad'],
                ]);
            }

            DB::commit();

            $transaccion->load('detallesTransacciones.articulo', 'detallesTransacciones.articuloLote');

            return response()->jsonResponse('Entrada de artículos creada', $transaccion, 201);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Find with trashed the specified resource from storage.
     */
    protected function findWithTrashed(string $id)
    {
        $transaccion = Transaccion::withTrashed()->where('tipo', Transaccion::TIPO_ENTRADA)->find($id);

        if (is_null($transaccion)) {
            throw new NotFoundHttpException('Entrada de artículos no encontrada');
        }

        return $transaccion;
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function softDestroy(string $id)
    {
        $transaccion = $this->findWithTrashed($id);

        $transaccion->delete();

        return response()->jsonResponse('Entrada de artículos desactivada', $transaccion, 200);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(int $id)
    {
        $transaccion = $this->findWithTrashed($id);

        $transaccion->restore();

        return response()->jsonResponse('Entrada de artículos activada', $transaccion, 200);
    }
}
