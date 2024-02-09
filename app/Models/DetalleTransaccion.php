<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleTransaccion extends Model
{
    protected $table = 'detalles_transacciones';

    public $timestamps = false;

    protected $fillable = [
        'transaccion_id',
        'articulo_id',
        'cantidad',
    ];

    public function transaccion()
    {
        return $this->belongsTo(Transaccion::class, 'transaccion_id');
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'articulo_id');
    }

    public function articuloLote()
    {
        return $this->hasOne(ArticuloLote::class, 'detalle_transaccion_id');
    }
}
