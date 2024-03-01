<?php

namespace App\Http\Controllers\Traits;

use App\Models\Articulo;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait ArticuloTrait
{
    /**
     * Método que retorna un query builder con los filtros aplicados.
     *
     * @param mixed $parametros
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function indexQueryBuilder(mixed $parametros): Builder
    {
        $queryBuilder = Articulo::with([
            'categoria',
            'unidad',
            'ubicacion',
            'articulosLotes' => function (Builder $query) {
                $query->where('cantidad', '>', 0)
                    ->orderBy('fecha_vencimiento', 'asc');
            }])
            ->where('tipo', Articulo::TIPO_ALMACENABLE)
            ->leftJoin('articulos_lotes', 'articulos.id', '=', 'articulos_lotes.articulo_id')
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
            $queryBuilder->where(function (Builder $query) use ($parametros) {
                $query->where('codigo', 'like', "%{$parametros['busqueda']}%")
                    ->orWhere('nombre', 'like', "%{$parametros['busqueda']}%");
            });
        }

        return $queryBuilder;
    }
}
