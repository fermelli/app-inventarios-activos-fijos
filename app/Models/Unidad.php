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
}
