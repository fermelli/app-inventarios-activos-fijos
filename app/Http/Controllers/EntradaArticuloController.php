<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\EntradaArticuloControllerTrait;
use App\Http\Requests\CrearEntradaArticulosRequest;
use App\Http\Requests\PaginacionConEliminadosOrdenDireccionBusquedaRequest;
use App\Models\Transaccion;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class EntradaArticuloController extends Controller
{
    use EntradaArticuloControllerTrait;

    /**
     * Display a listing of the resource.
     */
    public function index(PaginacionConEliminadosOrdenDireccionBusquedaRequest $request)
    {
        $parametros = $request->validated();
        $queryBuilder = Transaccion::with([
            'usuario',
            'anulador',
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
                'estado_entrada' => Transaccion::ESTADO_ENTRADA_VALIDA,
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaccion = $this->showDatos($id);

        return response()->jsonResponse('Entrada de artículos recuperada', $transaccion, 200);
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

    /**
     * Anular la transacción de entrada de artículos.
     */
    public function anular(string $id, Request $request)
    {
        $transaccion = $this->findWithTrashed($id);

        if ($transaccion->estado_entrada === Transaccion::ESTADO_ENTRADA_ANULADA) {
            throw new BadRequestHttpException('La entrada de artículos ya está anulada');
        }

        DB::beginTransaction();

        try {
            $transaccion->load('detallesTransacciones.articuloLote.articulo');

            $transaccion->update([
                'estado_entrada' => Transaccion::ESTADO_ENTRADA_ANULADA,
                'anulador_id' => $request->user()->id,
                'fecha_hora_anulacion' => now(),
            ]);

            $detallesTransacciones = $transaccion->detallesTransacciones;

            foreach ($detallesTransacciones as $detalleTransaccion) {
                $articuloLote = $detalleTransaccion->articuloLote;

                if ($articuloLote->cantidad == 0 || $detalleTransaccion->cantidad > $articuloLote->cantidad) {
                    $articulo = $articuloLote->articulo;
                    $mensaje = "No se puede anular la entrada de artículos, el artículo {$articulo->nombre} no tiene suficiente cantidad";

                    throw new BadRequestHttpException($mensaje);
                }

                $detalleTransaccion->articuloLote->update([
                    'cantidad' => $articuloLote->cantidad - $detalleTransaccion->cantidad,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }

        return response()->jsonResponse('Entrada de artículos anulada', $transaccion, 200);
    }
}
