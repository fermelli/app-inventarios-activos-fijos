<?php

namespace App\Http\Controllers\Traits;

use App\Models\Transaccion;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait EntradaArticuloControllerTrait
{
    /**
     * Find with trashed the specified resource from storage.
     */
    protected function findWithTrashed(string $id)
    {
        $transaccion = Transaccion::withTrashed()->where('tipo', Transaccion::TIPO_ENTRADA)->find($id);

        if (is_null($transaccion)) {
            throw new NotFoundHttpException('Entrada de artículos no encontrada');
        }

        return $transaccion;
    }

    public function showDatos(string $id)
    {
        $transaccion = $this->findWithTrashed($id);

        if (!is_null($transaccion->eliminado_en)) {
            throw new BadRequestHttpException('Entrada de artículos está desactivada');
        }

        $transaccion->load([
            'usuario',
            'anulador',
            'institucion' => function (Builder $query) {
                $query->withTrashed();
            },
            'detallesTransacciones.articulo' => function (Builder $query) {
                $query->withTrashed();
            },
            'detallesTransacciones.articulo.unidad',
            'detallesTransacciones.articuloLote',
        ]);

        return $transaccion;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param array $datos
     * @return \App\Models\Transaccion
     */
    public function guardarEntrada(array $datos): Transaccion
    {
        $transaccion = Transaccion::create($datos);
        $datosDetallesTransacciones = $datos['detalles_transacciones'];

        foreach ($datosDetallesTransacciones as $datosDetalleTransaccion) {
            $detalleTransaccion = $transaccion->detallesTransacciones()->create($datosDetalleTransaccion);
            
            $datosArticuloLote = $datosDetalleTransaccion['articulo_lote'] ?? [];
            
            $detalleTransaccion->articuloLote()->create([
                ...$datosArticuloLote,
                'articulo_id' => $datosDetalleTransaccion['articulo_id'],
                'cantidad' => $datosDetalleTransaccion['cantidad'],
            ]);
        }

        return $transaccion;
    }
}
