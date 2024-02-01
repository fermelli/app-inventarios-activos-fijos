<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    protected $table = 'unidades';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];

    public function articulos()
    {
        return $this->hasMany(Articulo::class, 'unidad_id');
    }

    public function getContadorArticulosAttribute()
    {
        return $this->articulos()->count();
    }
}
