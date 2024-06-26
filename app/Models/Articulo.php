<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Articulo extends Model
{
    use SoftDeletes;

    protected $table = 'articulos';

    public const CREATED_AT = 'creado_en';
    public const UPDATED_AT = 'actualizado_en';
    public const DELETED_AT = 'eliminado_en';

    public const TIPO_ALMACENABLE = 'almacenable';
    public const TIPO_ACTIVO_FIJO = 'activo fijo';

    public const ESTADO_ACTIVO_FIJO_ACTIVO = 'activo';
    public const ESTADO_ACTIVO_FIJO_EN_MANTENIMIENTO = 'en mantenimiento';
    public const ESTADO_ACTIVO_FIJO_DE_BAJA = 'de baja';

    protected $fillable = [
        'categoria_id',
        'unidad_id',
        'ubicacion_id',
        'codigo',
        'nombre',
        'institucion_id',
        'tipo',
        'estado_activo_fijo',
        'descripcion',
        'fecha_baja',
        'razon_baja',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id')->withTrashed();
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'unidad_id');
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id');
    }

    public function articulosLotes()
    {
        return $this->hasMany(ArticuloLote::class, 'articulo_id');
    }

    public function institucion()
    {
        return $this->belongsTo(Institucion::class, 'institucion_id');
    }

    public function asignacionesActivosFijos()
    {
        return $this->hasMany(AsignacionActivoFijo::class, 'activo_fijo_id');
    }

    public function asignacionActivoFijoActual()
    {
        return $this->hasOne(AsignacionActivoFijo::class, 'activo_fijo_id')
                    ->whereNull('fecha_devolucion');
    }
}
