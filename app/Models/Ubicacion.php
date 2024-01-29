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
}
