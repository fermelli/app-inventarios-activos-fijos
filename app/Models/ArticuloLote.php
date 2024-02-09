<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticuloLote extends Model
{
    protected $table = 'articulos_lotes';

    public $timestamps = false;

    protected $fillable = [
        'articulo_id',
        'detalle_transaccion_id',
        'lote',
        'fecha_vencimiento',
        'cantidad',
    ];

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'articulo_id');
    }

    public function detalleTransaccion()
    {
        return $this->belongsTo(DetalleTransaccion::class, 'detalle_transaccion_id');
    }
}
