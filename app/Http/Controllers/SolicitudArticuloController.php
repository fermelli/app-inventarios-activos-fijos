<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\SolicitudArticuloControllerTrait;
use App\Http\Requests\CrearSolicitudArticuloConSolicitanteRequest;
use App\Http\Requests\CrearSolicitudArticuloRequest;
use App\Http\Requests\PaginacionConEliminadosOrdenDireccionBusquedaRequest;
use App\Models\Transaccion;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SolicitudArticuloController extends Controller
{
    use SolicitudArticuloControllerTrait;

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

        $datos['solicitante_id'] = $request->user()->id;

        return $this->save($datos);
    }

    public function storeConSolicitante(CrearSolicitudArticuloConSolicitanteRequest $request)
    {
        $datos = $request->validated();

        return $this->save($datos);
    }

    protected function save($datos)
    {
        $numeroSolicitud = Transaccion::where('tipo', Transaccion::TIPO_SOLICITUD)
            ->max('numero_solicitud') + 1;

        DB::beginTransaction();

        try {
            $transaccion = Transaccion::create([
                ...$datos,
                'tipo' => Transaccion::TIPO_SOLICITUD,
                'numero_solicitud' => $numeroSolicitud,
                'estado_solicitud' => Transaccion::ESTADO_SOLICITUD_PENDIENTE,
            ]);
            $datosDetallesTransacciones = $datos['detalles_transacciones'];

            foreach ($datosDetallesTransacciones as $datosDetalleTransaccion) {
                $transaccion->detallesTransacciones()->create($datosDetalleTransaccion);
            }

            DB::commit();

            return response()->jsonResponse('Solicitud de artículo creada', $transaccion, 201);
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

        return response()->jsonResponse('Solicitud de artículo recuperada', $transaccion, 200);
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
