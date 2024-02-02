<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarInstitucionRequest;
use App\Http\Requests\ConEliminadosOrdenDireccionRequest;
use App\Http\Requests\CrearInstitucionRequest;
use App\Models\Institucion;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InstitucionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ConEliminadosOrdenDireccionRequest $request)
    {
        $parametros = $request->validated();
        $queryBuilder = Institucion::query();

        if (isset($parametros['con_eliminados'])) {
            $queryBuilder->withTrashed();
        }

        if (isset($parametros['orden_direccion'])) {
            $queryBuilder->orderBy('id', $parametros['orden_direccion']);
        }

        $instituciones = $queryBuilder->get();

        return response()->jsonResponse('Instituciones recuperadas', $instituciones, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CrearInstitucionRequest $request)
    {
        $institucion = Institucion::create($request->validated());

        return response()->jsonResponse('Institución creada', $institucion, 201);
    }

    protected function findWithTrashed(string $id)
    {
        $institucion = Institucion::withTrashed()->find($id);

        if (is_null($institucion)) {
            throw new NotFoundHttpException('Institución no encontrada');
        }

        return $institucion;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $institucion = $this->findWithTrashed($id);

        return response()->jsonResponse('Institución recuperada', $institucion, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActualizarInstitucionRequest $request, string $id)
    {
        $institucion = $this->findWithTrashed($id);

        $institucion->update($request->validated());

        return response()->jsonResponse('Institución actualizada', $institucion, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $institucion = $this->findWithTrashed($id);

        $institucion->forceDelete();

        return response()->jsonResponse('Institución eliminada', $institucion, 200);
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function softDestroy(int $id)
    {
        $institucion = $this->findWithTrashed($id);

        $institucion->delete();

        return response()->jsonResponse('Institución desactivada', $institucion, 200);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(int $id)
    {
        $articulo = $this->findWithTrashed($id);

        $articulo->restore();

        return response()->jsonResponse('Institución activada', $articulo, 200);
    }
}
