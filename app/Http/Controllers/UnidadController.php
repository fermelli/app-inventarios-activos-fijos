<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarUnidadRequest;
use App\Http\Requests\CrearUnidadRequest;
use App\Http\Requests\OrdenDireccionRequest;
use App\Models\Unidad;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UnidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(OrdenDireccionRequest $request)
    {
        $parametros = $request->validated();
        $unidades = Unidad::orderBy('id', $parametros['orden_direccion'])->get();

        return response()->jsonResponse('Unidades recuperadas', $unidades, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CrearUnidadRequest $request)
    {
        $unidad = Unidad::create($request->validated());

        return response()->jsonResponse('Unidad creada', $unidad, 201);
    }

    protected function find(string $id)
    {
        $unidad = Unidad::find($id);

        if (is_null($unidad)) {
            throw new NotFoundHttpException('Unidad no encontrada');
        }

        return $unidad;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $unidad = $this->find($id);

        return response()->jsonResponse('Unidad recuperada', $unidad, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActualizarUnidadRequest $request, string $id)
    {
        $unidad = $this->find($id);

        $unidad->update($request->validated());

        return response()->jsonResponse('Unidad actualizada', $unidad, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unidad = $this->find($id);

        $unidad->delete();

        return response()->jsonResponse('Unidad eliminada', $unidad, 200);
    }
}
