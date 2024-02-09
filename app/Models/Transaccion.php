<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaccion extends Model
{
    use SoftDeletes;

    protected $table = 'transacciones';

    public const CREATED_AT = 'creado_en';
    public const UPDATED_AT = 'actualizado_en';
    public const DELETED_AT = 'eliminado_en';

    protected $fillable = [
        'usuario_id',
        'institucion_id',
        'fecha',
        'tipo',
        'comprobante',
        'numero_comprobante',
        'observacion',
    ];

    public const TIPO_ENTRADA = 'entrada';

    public const TIPOS = [
        self::TIPO_ENTRADA,
    ];

    public const COMPROBANTES = [
        'factura',
        'recibo',
        'nota',
        'boleta',
        'otro',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function institucion()
    {
        return $this->belongsTo(Institucion::class, 'institucion_id');
    }

    public function detallesTransacciones()
    {
        return $this->hasMany(DetalleTransaccion::class, 'transaccion_id');
    }
}
