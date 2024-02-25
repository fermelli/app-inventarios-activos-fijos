<?php

namespace App\Http\Controllers;

use App\Http\Requests\CrearAsignacionActivoFijoRequest;
use App\Http\Requests\DevolverAsignacionActivoFijoRequest;
use App\Http\Requests\PaginacionConEliminadosOrdenDireccionRequest;
use App\Models\AsignacionActivoFijo;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AsignacionActivoFijoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaginacionConEliminadosOrdenDireccionRequest $request)
    {
        $parametros = $request->validated();
        $queryBuilder = AsignacionActivoFijo::with([
            'activoFijo.categoria',
            'activoFijo.institucion',
            'ubicacion',
            'asignadoA',
            'usuario',
            'devueltoA',
        ]);

        if (isset($parametros['orden_direccion'])) {
            $queryBuilder->orderBy('id', $parametros['orden_direccion']);
        }

        $asignacionesActivosFijos = $queryBuilder->paginate(
            $parametros['items_por_pagina'],
            ['*'],
            'pagina',
            $parametros['pagina']
        );

        return response()->jsonResponsePaginado(
            'Asignaciones de activos fijos recuperadas',
            $asignacionesActivosFijos,
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CrearAsignacionActivoFijoRequest $request)
    {
        $datos = $request->validated();
        $activoFijoId = $datos['activo_fijo_id'];

        $asignacionActivoFijoActiva = AsignacionActivoFijo::where('activo_fijo_id', $activoFijoId)
                                                        ->whereNull('fecha_devolucion')
                                                        ->first();

        if (!is_null($asignacionActivoFijoActiva)) {
            throw new BadRequestHttpException('El activo fijo ya se encuentra asignado');
        }

        $asignacionActivoFijo = AsignacionActivoFijo::create([
            ...$datos,
            'usuario_id' => $request->user()->id,
        ]);

        return response()->jsonResponse('Asignación de activo fijo creada', $asignacionActivoFijo, 201);
    }

    public function find(string $id)
    {
        $asignacionActivoFijo = AsignacionActivoFijo::find($id);

        if (is_null($asignacionActivoFijo)) {
            throw new NotFoundHttpException('Asignación de activo fijo no encontrada');
        }

        $asignacionActivoFijo->load([
            'activoFijo.categoria',
            'activoFijo.institucion',
            'ubicacion',
            'asignadoA',
            'usuario',
            'devueltoA',
        ]);

        return $asignacionActivoFijo;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $asignacionActivoFijo = $this->find($id);

        return response()->jsonResponse('Asignación de activo fijo recuperada', $asignacionActivoFijo, 200);
    }

    /**
     * Devuelve la asignación de activo fijo especificada.
     */
    public function devolver(string $id, DevolverAsignacionActivoFijoRequest $request)
    {
        $datos = $request->validated();
        $asignacionActivoFijo = $this->find($id);

        if (!is_null($asignacionActivoFijo->fecha_devolucion)) {
            throw new BadRequestHttpException('El activo fijo ya fue devuelto');
        }

        $asignacionActivoFijo->update([
            ...$datos,
            'devuelto_a_id' => $request->user()->id,
        ]);

        return response()->jsonResponse('Activo fijo devuelto', $asignacionActivoFijo, 200);
    }
}
