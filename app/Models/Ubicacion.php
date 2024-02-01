<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    protected $table = 'ubicaciones';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
    ];

    public function articulos()
    {
        return $this->hasMany(Articulo::class, 'ubicacion_id');
    }

    public function getContadorArticulosAttribute()
    {
        return $this->articulos()->count();
    }
}
