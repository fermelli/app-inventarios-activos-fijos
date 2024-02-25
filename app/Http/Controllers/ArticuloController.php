<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarArticuloRequest;
use App\Http\Requests\CrearArticuloRequest;
use App\Http\Requests\IndexArticuloControllerRequest;
use App\Models\Articulo;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexArticuloControllerRequest $request)
    {
        $parametros = $request->validated();
        $queryBuilder = Articulo::with(['categoria', 'unidad', 'ubicacion', 'articulosLotes' => function (Builder $query) {
            $query->where('cantidad', '>', 0)
                ->orderBy('fecha_vencimiento', 'asc');
        }])->leftJoin('articulos_lotes', 'articulos.id', '=', 'articulos_lotes.articulo_id')
            ->select('articulos.*', DB::raw('IFNULL(SUM(articulos_lotes.cantidad), 0) as cantidad'))
            ->groupBy('articulos.id');

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
            $queryBuilder->where('codigo', 'like', "%{$parametros['busqueda']}%")
                        ->orWhere('nombre', 'like', "%{$parametros['busqueda']}%");
        }

        $articulos = $queryBuilder->paginate($parametros['items_por_pagina'], ['*'], 'pagina', $parametros['pagina']);

        return response()->jsonResponsePaginado('Productos recuperados', $articulos, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CrearArticuloRequest $request)
    {
        $articulo = Articulo::create($request->validated());

        return response()->jsonResponse('Producto creado', $articulo, 201);
    }

    protected function findWithTrashed(string $id)
    {
        $articulo = Articulo::withTrashed()->find($id);

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
