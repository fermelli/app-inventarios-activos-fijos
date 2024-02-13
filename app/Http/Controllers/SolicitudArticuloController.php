<?php

namespace App\Http\Controllers;

use App\Http\Requests\CrearSolicitudArticuloRequest;
use App\Http\Requests\PaginacionConEliminadosOrdenDireccionBusquedaRequest;
use App\Models\Transaccion;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SolicitudArticuloController extends Controller
{
    /**
     * Display a listing of the resource with pagination.
     */
    public function index(PaginacionConEliminadosOrdenDireccionBusquedaRequest $request)
    {
        $solicitudesArticulos = $this->findIndex($request);

        return response()->jsonResponsePaginado('Solicitudes de artículos recuperadas', $solicitudesArticulos, 200);
    }

    /**
     * Display a listing of the resource with pagination for the authenticated user.
     */
    public function indexUsuario(PaginacionConEliminadosOrdenDireccionBusquedaRequest $request)
    {
        $solicitudesArticulos = $this->findIndex($request, $request->user()->id);

        return response()->jsonResponsePaginado(
            'Solicitudes de artículos del usuario recuperadas',
            $solicitudesArticulos,
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
            'solicitante',
            'detallesTransacciones.articulo' => function (Builder $query) {
                $query->withTrashed();
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
                                            ->paginate(
                                                $parametros['items_por_pagina'],
                                                ['*'],
                                                'pagina',
                                                $parametros['pagina']
                                            );

        return $solicitudesArticulos;
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(CrearSolicitudArticuloRequest $request)
    {
        $datos = $request->validated();

        $numeroSolicitud = Transaccion::where('tipo', Transaccion::TIPO_SOLICITUD)
            ->max('numero_solicitud') + 1;

        DB::beginTransaction();

        try {
            $transaccion = Transaccion::create([
                ...$datos,
                'tipo' => Transaccion::TIPO_SOLICITUD,
                'solicitante_id' => $request->user()->id,
                'numero_solicitud' => $numeroSolicitud,
            ]);
            $datosDetallesTransacciones = $datos['detalles_transacciones'];

            foreach ($datosDetallesTransacciones as $datosDetalleTransaccion) {
                $transaccion->detallesTransacciones()->create($datosDetalleTransaccion);
            }

            DB::commit();
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
        $transaccion = Transaccion::withTrashed()->find($id);

        if (is_null($transaccion)) {
            throw new NotFoundHttpException('Solicitud de artículo no encontrada');
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

        return response()->jsonResponse('Solicitud de artículos desactivada', $transaccion, 200);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(int $id)
    {
        $transaccion = $this->findWithTrashed($id);

        $transaccion->restore();

        return response()->jsonResponse('Solicitud de artículos activada', $transaccion, 200);
    }
}
