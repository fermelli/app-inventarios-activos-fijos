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

    protected $fillable = [
        'categoria_id',
        'unidad_id',
        'ubicacion_id',
        'codigo',
        'nombre',
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class, 'unidad_id');
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion_id');
    }
}
