<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActualizarCategoriaRequest;
use App\Http\Requests\CrearCategoriaRequest;
use App\Models\Categoria;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categorias = Categoria::with('categoriaPadre')->get();

        return response()->json([
            'categorias' => $categorias,
        ]);
    }

    public function indexPadresConHijas()
    {
        $categorias = Categoria::with('categoriasHijas.categoriasHijas')->where('categoria_padre_id', null)->get();

        return response()->json([
            'categorias' => $categorias,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CrearCategoriaRequest $request)
    {
        $categoria = Categoria::create($request->validated());

        return response()->json([
            'categoria' => $categoria,
        ]);
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
     * Find the specified resource from storage.
     */
    protected function find(int $id): Categoria
    {
        $categoria = $this->findWithTrashed($id);

        if ($categoria->trashed()) {
            throw new BadRequestException('Categoría desactivada');
        }

        return $categoria;
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $categoria = $this->find($id);

        $categoria->load(['categoriaPadre', 'categoriasHijas.categoriasHijas']);

        return response()->json([
            'categoria' => $categoria,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ActualizarCategoriaRequest $request, int $id)
    {
        $categoria = $this->find($id);

        $categoria->update($request->validated());

        return response()->json([
            'categoria' => $categoria,
        ]);
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

        return response()->json([
            'categoria' => $categoria,
        ]);
    }

    /**
     * Soft delete the specified resource from storage.
     */
    public function softDestroy(int $id)
    {
        $categoria = $this->find($id);

        $categoria->delete();

        return response()->json([
            'categoria' => $categoria,
        ]);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(int $id)
    {
        $categoria = $this->findWithTrashed($id);

        $categoria->restore();

        return response()->json([
            'categoria' => $categoria,
        ]);
    }
}
