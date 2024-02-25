<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Institucion extends Model
{
    use SoftDeletes;

    protected $table = 'instituciones';

    public const CREATED_AT = 'creado_en';
    public const UPDATED_AT = 'actualizado_en';
    public const DELETED_AT = 'eliminado_en';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
    ];

    public function articulos()
    {
        return $this->hasMany(Articulo::class, 'institucion_id');
    }
}
