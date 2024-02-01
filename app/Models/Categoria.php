<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categoria extends Model
{
    use SoftDeletes;
    
    protected $table = 'categorias';

    public $timestamps = false;

    public const DELETED_AT = 'eliminado_en';
    
    protected $fillable = [
        'nombre',
        'categoria_padre_id',
    ];
    
    public function categoriaPadre()
    {
        return $this->belongsTo(Categoria::class, 'categoria_padre_id');
    }

    public function categoriasHijas()
    {
        return $this->hasMany(Categoria::class, 'categoria_padre_id')->with('categoriasHijas');
    }

    public function categoriasHijasInmediatas()
    {
        return $this->hasMany(Categoria::class, 'categoria_padre_id');
    }

    public function articulos()
    {
        return $this->hasMany(Articulo::class, 'categoria_id');
    }

    public function getContadorArticulosAttribute()
    {
        return $this->articulos()->count();
    }
}
