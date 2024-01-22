<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarCategoriaRequest;
use App\Http\Requests\ConEliminadosOrdenDireccionRequest;
use App\Http\Requests\CrearCategoriaRequest;
use App\Models\Categoria;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ConEliminadosOrdenDireccionRequest
     */
    public function index(ConEliminadosOrdenDireccionRequest $request)
    {
        $parametros = $request->validated();
        $queryBuilder = Categoria::with(['categoriaPadre' => function (Builder $query) {
            $query->withTrashed();
        }, 'categoriasHijas']);

        if ($parametros['con_eliminados']) {
            $queryBuilder->withTrashed();
        }

        $queryBuilder->orderBy('id', $parametros['orden_direccion']);

        $categorias = $queryBuilder->get();

        return response()->jsonResponse('Categorías recuperadas', $categorias, 200);
    }

    public function indexPadresConHijas(ConEliminadosOrdenDireccionRequest $request)
    {
        $parametros = $request->validated();
        $queryBuilder = Categoria::with(['categoriasHijas' => function (Builder $query) use ($parametros) {
            $query->with(['categoriasHijas' => function (Builder $query) use ($parametros) {
                $query->withTrashed();

                if ($parametros['con_eliminados']) {
                    $query->withTrashed();
                }

                $query->orderBy('id', $parametros['orden_direccion']);
            }]);
        }]);

        if ($parametros['con_eliminados']) {
            $queryBuilder->withTrashed();
        }
        
        $queryBuilder->where('categoria_padre_id', null)->orderBy('id', $parametros['orden_direccion']);
        
        $categoria = $queryBuilder->get();

        return response()->jsonResponse('Categoría recuperada', $categoria, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CrearCategoriaRequest $request)
    {
        $categoria = Categoria::create($request->validated());

        return response()->jsonResponse('Categoría creada', $categoria, 201);
    }

    /**
     * Find with trashed the specified resource from storage.
     */
    public function findWithTrashed(int $id)
    {
        $categoria = Categoria::withTrashed()->find($id);

        if (is_null($categoria)) {
            throw new NotFoundHttpException('Categoría no encontrada');
        }

        return $categoria;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $categoria = $this->findWithTrashed($id);

        $categoria->load(['categoriaPadre', 'categoriasHijas.categoriasHijas']);

        return response()->jsonResponse('Categoría recuperada', $categoria, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActualizarCategoriaRequest $request, int $id)
    {
        $categoria = $this->findWithTrashed($id);

        $categoria->update($request->validated());

        return response()->jsonResponse('Categoría actualizada', $categoria, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $categoria = $this->findWithTrashed($id);

        if ($categoria->categoriasHijas->isNotEmpty()) {
            throw new BadRequestException('No se puede eliminar una categoría que tiene categorías hijas');
        }

        $categoria->forceDelete();

        return response()->jsonResponse('Categoría eliminada', $categoria, 200);
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function softDestroy(int $id)
    {
        $categoria = $this->findWithTrashed($id);

        $categoria->delete();

        return response()->jsonResponse('Categoría desactivada', $categoria, 200);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(int $id)
    {
        $categoria = $this->findWithTrashed($id);

        $categoria->restore();

        return response()->jsonResponse('Categoría activada', $categoria, 200);
    }
}
