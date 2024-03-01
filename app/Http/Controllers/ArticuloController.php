<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\ArticuloTrait;
use App\Http\Requests\ActualizarArticuloRequest;
use App\Http\Requests\CrearArticuloRequest;
use App\Http\Requests\IndexArticuloControllerRequest;
use App\Models\Articulo;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticuloController extends Controller
{
    use ArticuloTrait;
    
    /**
     * Display a listing of the resource.
     */
    public function index(IndexArticuloControllerRequest $request)
    {
        $parametros = $request->validated();
        $queryBuilder = $this->indexQueryBuilder($parametros);

        $articulos = $queryBuilder->paginate($parametros['items_por_pagina'], ['*'], 'pagina', $parametros['pagina']);

        return response()->jsonResponsePaginado('Productos recuperados', $articulos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CrearArticuloRequest $request)
    {
        $datosArticulo = $request->validated();
        $articulo = Articulo::create([
            ...$datosArticulo,
            'tipo' => Articulo::TIPO_ALMACENABLE,
        ]);

        return response()->jsonResponse('Producto creado', $articulo, 201);
    }

    protected function findWithTrashed(string $id)
    {
        $articulo = Articulo::withTrashed()->where('tipo', Articulo::TIPO_ALMACENABLE)->find($id);

        if (is_null($articulo)) {
            throw new NotFoundHttpException('Artículo no encontrado');
        }

        return $articulo;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $articulo = $this->findWithTrashed($id);

        $articulo->load(['categoria', 'unidad', 'ubicacion']);

        return response()->jsonResponse('Artículo recuperado', $articulo, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActualizarArticuloRequest $request, string $id)
    {
        $articulo = $this->findWithTrashed($id);

        $articulo->update($request->validated());

        return response()->jsonResponse('Artículo actualizado', $articulo, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $articulo = $this->findWithTrashed($id);

        $articulo->forceDelete();

        return response()->jsonResponse('Artículo eliminado', $articulo, 200);
    }

        /**
     * Soft delete the specified resource from storage.
     */
    public function softDestroy(int $id)
    {
        $articulo = $this->findWithTrashed($id);

        $articulo->delete();

        return response()->jsonResponse('Artículo desactivado', $articulo, 200);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(int $id)
    {
        $articulo = $this->findWithTrashed($id);

        $articulo->restore();

        return response()->jsonResponse('Artículo activado', $articulo, 200);
    }
}
