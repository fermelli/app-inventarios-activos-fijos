<?php

namespace App\Http\Controllers\Traits;

use App\Models\Transaccion;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait SolicitudArticuloControllerTrait
{
    /**
     * Find with trashed the specified resource from storage.
     */
    protected function findWithTrashed(string $id)
    {
        $transaccion = Transaccion::withTrashed()->where('tipo', Transaccion::TIPO_SOLICITUD)->find($id);

        if (is_null($transaccion)) {
            throw new NotFoundHttpException('Solicitud de artículo no encontrada');
        }

        return $transaccion;
    }

    public function showDatos(string $id)
    {
        $transaccion = $this->findWithTrashed($id);

        if (!is_null($transaccion->eliminado_en)) {
            throw new BadRequestHttpException('Solicitud de artículo está desactivada');
        }

        $transaccion->load([
            'usuario',
            'solicitante',
            'despachante',
            'anulador',
            'detallesTransacciones.articulo.unidad',
            'detallesTransacciones.articulo' => function (Builder $query) {
                $query->withTrashed()
                    ->leftJoin('articulos_lotes', 'articulos.id', '=', 'articulos_lotes.articulo_id')
                    ->select([
                        'articulos.*',
                        DB::raw('SUM(articulos_lotes.cantidad) as cantidad'),
                    ])
                    ->groupBy('articulos.id');
            },
        ]);

        return $transaccion;
    }
}
