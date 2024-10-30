<?php

namespace App\Http\Controllers\Traits;

use App\Models\Articulo;
use Illuminate\Contracts\Database\Eloquent\Builder;

trait ActivoFijoTrait
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
            'institucion',
            'asignacionActivoFijoActual.usuario',
            'asignacionActivoFijoActual.ubicacion',
        ])->where('tipo', Articulo::TIPO_ACTIVO_FIJO);

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
