<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarUbicacionRequest;
use App\Http\Requests\CrearUbicacionRequest;
use App\Http\Requests\OrdenDireccionRequest;
use App\Models\Ubicacion;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UbicacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(OrdenDireccionRequest $request)
    {
        $parametros = $request->validated();
        $ubicaciones = Ubicacion::orderBy('id', $parametros['orden_direccion'])->get();

        return response()->jsonResponse('Unidades recuperadas', $ubicaciones, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CrearUbicacionRequest $request)
    {
        $ubicacion = Ubicacion::create($request->validated());

        return response()->jsonResponse('Ubicación creada', $ubicacion, 201);
    }

    protected function find(string $id)
    {
        $ubicacion = Ubicacion::find($id);

        if (is_null($ubicacion)) {
            throw new NotFoundHttpException('Ubicación no encontrada');
        }

        return $ubicacion;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $ubicacion = $this->find($id);

        return response()->jsonResponse('Ubicación recuperada', $ubicacion, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActualizarUbicacionRequest $request, string $id)
    {
        $ubicacion = $this->find($id);

        $ubicacion->update($request->validated());

        return response()->jsonResponse('Ubicación actualizada', $ubicacion, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ubicacion = $this->find($id);

        $ubicacion->delete();

        return response()->jsonResponse('Ubicación eliminada', $ubicacion, 200);
    }
}
