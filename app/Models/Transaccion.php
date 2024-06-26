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
        'solicitante_id',
        'fecha',
        'tipo',
        'comprobante',
        'numero_comprobante',
        'estado_entrada',
        'numero_solicitud',
        'estado_solicitud',
        'fecha_hora_atencion',
        'despachante_id',
        'fecha_hora_entrega',
        'anulador_id',
        'fecha_hora_anulacion',
        'observacion',
    ];

    public const TIPO_ENTRADA = 'entrada';
    public const TIPO_SOLICITUD = 'solicitud';

    public const TIPOS = [
        self::TIPO_ENTRADA,
        self::TIPO_SOLICITUD,
    ];

    public const COMPROBANTE_FACTURA = 'factura';
    public const COMPROBANTE_RECIBO = 'recibo';
    public const COMPROBANTE_NOTA = 'nota';
    public const COMPROBANTE_BOLETA = 'boleta';
    public const COMPROBANTE_OTRO = 'otro';
    public const COMPROBANTE_IMPORTACION_DATOS = 'importacion datos';

    public const COMPROBANTES = [
        self::COMPROBANTE_FACTURA,
        self::COMPROBANTE_RECIBO,
        self::COMPROBANTE_NOTA,
        self::COMPROBANTE_BOLETA,
        self::COMPROBANTE_OTRO,
        self::COMPROBANTE_IMPORTACION_DATOS,
    ];

    public const ESTADO_ENTRADA_VALIDA = 'valida';
    public const ESTADO_ENTRADA_ANULADA = 'anulada';

    public const ESTADO_SOLICITUD_PENDIENTE = 'pendiente';
    public const ESTADO_SOLICITUD_APROBADA = 'aprobada';
    public const ESTADO_SOLICITUD_RECHAZADA = 'rechazada';
    public const ESTADO_SOLICITUD_ENTREGADA = 'entregada';
    public const ESTADO_SOLICITUD_ANULADA = 'anulada';

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

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    public function despachante()
    {
        return $this->belongsTo(User::class, 'despachante_id');
    }

    public function anulador()
    {
        return $this->belongsTo(User::class, 'anulador_id');
    }
}
