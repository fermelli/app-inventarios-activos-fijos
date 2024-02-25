<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsignacionActivoFijo extends Model
{
    protected $table = 'asignaciones_activos_fijos';

    public const CREATED_AT = 'creado_en';
    public const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'activo_fijo_id',
        'asignado_a_id',
        'ubicacion_id',
        'usuario_id',
        'devuelto_a_id',
        'observacion_asignacion',
        'observacion_devolucion',
        'fecha_asignacion',
        'fecha_devolucion',
    ];

    public function activoFijo()
    {
        return $this->belongsTo(Articulo::class, 'activo_fijo_id');
    }

    public function asignadoA()
    {
        return $this->belongsTo(User::class, 'asignado_a_id');
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function devueltoA()
    {
        return $this->belongsTo(User::class, 'devuelto_a_id');
    }
}
