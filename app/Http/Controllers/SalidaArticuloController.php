<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SolicitudArticuloControllerTrait;
use App\Http\Requests\PaginacionConEliminadosOrdenDireccionBusquedaRequest;
use App\Models\Transaccion;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SalidaArticuloController extends Controller
{
    use SolicitudArticuloControllerTrait;

    /**
     * Display a listing of the resource with pagination.
     */
    public function index(PaginacionConEliminadosOrdenDireccionBusquedaRequest $request)
    {
        $salidassArticulos = $this->findIndex($request);

        return response()->jsonResponsePaginado('Salidas de artículos recuperadas', $salidassArticulos, 200);
    }

    /**
     * Display a listing of the resource with pagination for the authenticated user.
     */
    public function indexUsuario(PaginacionConEliminadosOrdenDireccionBusquedaRequest $request)
    {
        $salidassArticulos = $this->findIndex($request, $request->user()->id);

        return response()->jsonResponsePaginado(
            'Salidas de artículos del usuario recuperadas',
            $salidassArticulos,
            200
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function findIndex(
        PaginacionConEliminadosOrdenDireccionBusquedaRequest $request,
        int | null $usuarioId = null
    )
    {
        $parametros = $request->validated();
        $queryBuilder = Transaccion::with([
            'usuario',
            'solicitante',
            'despachante',
            'anulador',
            'detallesTransacciones.articulo' => function (Builder $query) {
                $query->withTrashed()
                    ->leftJoin('articulos_lotes', 'articulos.id', '=', 'articulos_lotes.articulo_id')
                    ->select([
                        'articulos.*',
                        DB::raw('SUM(articulos_lotes.cantidad) as cantidad'),
                    ])
                    ->groupBy('articulos.id');
            },
            'detallesTransacciones.articulo.unidad',
        ]);

        if (isset($parametros['con_eliminados'])) {
            $queryBuilder->withTrashed();
        }

        if (isset($parametros['orden_direccion'])) {
            $queryBuilder->orderBy('id', $parametros['orden_direccion']);
        }

        if (isset($parametros['busqueda'])) {
            $queryBuilder->where('numero_solicitud', 'like', "%{$parametros['busqueda']}%");
        }

        if (!is_null($usuarioId)) {
            $queryBuilder->where('solicitante_id', $usuarioId);
        }

        $solicitudesArticulos = $queryBuilder->where('tipo', Transaccion::TIPO_SOLICITUD)
                                            ->where('estado_solicitud', '<>', Transaccion::ESTADO_SOLICITUD_PENDIENTE)
                                            ->paginate(
                                                $parametros['items_por_pagina'],
                                                ['*'],
                                                'pagina',
                                                $parametros['pagina']
                                            );

        return $solicitudesArticulos;
    }

    /**
     * Find the specified resource from storage.
     */
    protected function find(string $id)
    {
        $transaccion = $this->findWithTrashed($id);

        if (!is_null($transaccion->eliminado_en)) {
            throw new BadRequestHttpException('Solicitud de artículo está desactivada');
        }

        return $transaccion;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaccion = $this->showDatos($id);

        return response()->jsonResponse('Salida de artículo recuperada', $transaccion, 200);
    }

    /**
     * Aprobar solicitud de artículos.
     */
    public function aprobar(string $id, Request $request)
    {
        $transaccion = $this->find($id);

        if ($transaccion->estado_solicitud != Transaccion::ESTADO_SOLICITUD_PENDIENTE) {
            throw new BadRequestHttpException('Solicitud de artículo no está pendiente');
        }

        DB::beginTransaction();

        try {
            $transaccion->load([
                'detallesTransacciones.articulo.articulosLotes' => function ($query) {
                    $query->where('cantidad', '>', 0)
                        ->orderBy('fecha_vencimiento', 'asc');
                },
            ]);

            $transaccion->update([
                'estado_solicitud' => Transaccion::ESTADO_SOLICITUD_APROBADA,
                'usuario_id' => $request->user()->id,
                'fecha_hora_atencion' => now(),
            ]);

            $detallesTransacciones = $transaccion->detallesTransacciones;

            foreach ($detallesTransacciones as $detalleTransaccion) {
                $articulosLotes = $detalleTransaccion->articulo->articulosLotes;
                $cantidadTotalArticulosLotes = $articulosLotes->sum('cantidad');
                $cantidadSalida = $detalleTransaccion->cantidad;
                
                if ($cantidadSalida > $cantidadTotalArticulosLotes) {
                    throw new BadRequestHttpException(
                        "No hay suficientes existencias para el artículo {$detalleTransaccion->articulo->nombre}"
                    );
                }

                foreach ($articulosLotes as $articuloLote) {
                    if ($articuloLote->cantidad >= $cantidadSalida) {
                        $articuloLote->cantidad -= $cantidadSalida;
                        $articuloLote->save();

                        $detalleTransaccion->articulosLotes()->attach($articuloLote->id, [
                            'cantidad' => $cantidadSalida,
                        ]);

                        break;
                    }

                    $cantidadSalida -= $articuloLote->cantidad;
                    $cantidadAnterior = $articuloLote->cantidad;
                    $articuloLote->cantidad = 0;
                    $articuloLote->save();

                    $detalleTransaccion->articulosLotes()->attach($articuloLote->id, [
                        'cantidad' => $cantidadAnterior,
                    ]);
                }
            }
            
            DB::commit();

            return response()->jsonResponse('Salida de artículos aprobada', $transaccion, 200);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }

    /**
     * Rechazar solicitud de artículos.
     */
    public function rechazar(string $id, Request $request)
    {
        $transaccion = $this->find($id);

        if ($transaccion->estado_solicitud != Transaccion::ESTADO_SOLICITUD_PENDIENTE) {
            throw new BadRequestHttpException('Solicitud de artículo no está pendiente');
        }

        $transaccion->update([
            'estado_solicitud' => Transaccion::ESTADO_SOLICITUD_RECHAZADA,
            'usuario_id' => $request->user()->id,
            'fecha_hora_atencion' => now(),
        ]);

        return response()->jsonResponse('Salida de artículos rechazada', $transaccion, 200);
    }

    public function entregar(string $id, Request $request)
    {
        $transaccion = $this->find($id);

        if ($transaccion->estado_solicitud != Transaccion::ESTADO_SOLICITUD_APROBADA) {
            throw new BadRequestHttpException('Solicitud de artículo no está aprobada');
        }

        $transaccion->update([
            'estado_solicitud' => Transaccion::ESTADO_SOLICITUD_ENTREGADA,
            'despachante_id' => $request->user()->id,
            'fecha_hora_entrega' => now(),
        ]);

        return response()->jsonResponse('Salida de artículos entregada', $transaccion, 200);
    }

    public function anular(string $id)
    {
        $transaccion = $this->find($id);

        if ($transaccion->estado_solicitud != Transaccion::ESTADO_SOLICITUD_APROBADA) {
            throw new BadRequestHttpException('Solicitud de artículo no está aprobada');
        }

        DB::beginTransaction();

        try {
            $transaccion->load(['detallesTransacciones.articulosLotes']);

            $transaccion->update([
                'estado_solicitud' => Transaccion::ESTADO_SOLICITUD_ANULADA,
                'anulador_id' => request()->user()->id,
                'fecha_hora_anulacion' => now(),
            ]);

            $detallesTransacciones = $transaccion->detallesTransacciones;

            foreach ($detallesTransacciones as $detalleTransaccion) {
                $articulosLotes = $detalleTransaccion->articulosLotes;

                foreach ($articulosLotes as $articuloLote) {
                    $articuloLote->cantidad += $articuloLote->pivot->cantidad;
                    $articuloLote->save();
                }
            }
            
            DB::commit();
    
            return response()->jsonResponse('Salida de artículos devuelta', $transaccion, 200);
        } catch (\Exception $e) {
            DB::rollBack();

            throw $e;
        }
    }
}
