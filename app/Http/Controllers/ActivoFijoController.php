<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarActivoFijoRequest;
use App\Http\Requests\CrearActivoFijoRequest;
use App\Http\Requests\DarBajaActivoFijoRequest;
use App\Http\Requests\IndexArticuloControllerRequest;
use App\Models\Articulo;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ActivoFijoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexArticuloControllerRequest $request)
    {
        $parametros = $request->validated();
        $queryBuilder = Articulo::with(['categoria', 'institucion', 'asignacionActivoFijoActual'])->where('tipo', Articulo::TIPO_ACTIVO_FIJO);

        if (isset($parametros['con_eliminados'])) {
            $queryBuilder->withTrashed();
        }

        if (isset($parametros['orden_direccion'])) {
            $queryBuilder->orderBy('id', $parametros['orden_direccion']);
        }

        if (isset($parametros['categoria_id'])) {
            $queryBuilder->where('categoria_id', $parametros['categoria_id']);
        }

        if (isset($parametros['busqueda'])) {
            $queryBuilder->where(function (Builder $query) use ($parametros) {
                $query->where('codigo', 'like', "%{$parametros['busqueda']}%")
                    ->orWhere('nombre', 'like', "%{$parametros['busqueda']}%");
            });
        }

        $activosFijos = $queryBuilder->paginate(
            $parametros['items_por_pagina'],
            ['*'],
            'pagina',
            $parametros['pagina']
        );

        return response()->jsonResponsePaginado('Activos fijos recuperados', $activosFijos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CrearActivoFijoRequest $request)
    {
        $datosActivoFijo = $request->validated();
        $activoFijo = Articulo::create([
            ...$datosActivoFijo,
            'tipo' => Articulo::TIPO_ACTIVO_FIJO,
            'estado_activo_fijo' => Articulo::ESTADO_ACTIVO_FIJO_ACTIVO,
        ]);

        return response()->jsonResponse('Activo Fijo creado', $activoFijo, 201);
    }

    protected function findWithTrashed(string $id)
    {
        $activoFijo = Articulo::withTrashed()->where('tipo', Articulo::TIPO_ACTIVO_FIJO)->find($id);

        if (is_null($activoFijo)) {
            throw new NotFoundHttpException('Activo Fijo no encontrado');
        }

        return $activoFijo;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $activoFijo = $this->findWithTrashed($id);

        $activoFijo->load([
            'categoria',
            'institucion',
            'asignacionActivoFijoActual',
            'asignacionesActivosFijos.asignadoA',
            'asignacionesActivosFijos.ubicacion',
            'asignacionesActivosFijos.usuario',
            'asignacionesActivosFijos.devueltoA',
        ]);

        return response()->jsonResponse('Activo Fijo recuperado', $activoFijo, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActualizarActivoFijoRequest $request, string $id)
    {
        $activoFijo = $this->findWithTrashed($id);

        $activoFijo->update($request->validated());

        return response()->jsonResponse('Activo Fijo actualizado', $activoFijo, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activoFijo = $this->findWithTrashed($id);

        $activoFijo->forceDelete();

        return response()->jsonResponse('Activo Fijo eliminado', null, 200);
    }

    /**
     * Dar de baja un activo fijo.
     */
    public function darBaja(string $id, DarBajaActivoFijoRequest $request)
    {
        $datos = $request->validated();
        $activoFijo = $this->findWithTrashed($id);

        if ($activoFijo->estado_activo_fijo === Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA) {
            throw new BadRequestHttpException('El activo fijo ya se encuentra dado de baja');
        }

        $asignacionActivoFijoActual = $activoFijo->load('asignacionActivoFijoActual');

        if (!is_null($asignacionActivoFijoActual->asignacionActivoFijoActual)) {
            throw new BadRequestHttpException('El activo fijo se encuentra asignado, no se puede dar de baja');
        }

        $activoFijo->update([
            ...$datos,
            'estado_activo_fijo' => Articulo::ESTADO_ACTIVO_FIJO_DE_BAJA,
        ]);

        return response()->jsonResponse('Activo Fijo dado de baja', $activoFijo, 200);
    }
}
