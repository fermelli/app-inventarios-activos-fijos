<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'usuarios';

    /**
     * Custom name for "created_at" and "updated_at" columns.
     */
    public const CREATED_AT = 'creado_en';
    public const UPDATED_AT = 'actualizado_en';
    public const DELETED_AT = 'eliminado_en';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'correo_electronico',
        'password',
        'rol',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public const ROL_ADMINISTRADOR = 'administrador';
    public const ROL_PERSONAL = 'personal';

    public function estaActivo(): bool
    {
        return is_null($this->eliminado_en);
    }

    public function asignacionesActivosFijos()
    {
        return $this->hasMany(AsignacionActivoFijo::class, 'asignado_a_id');
    }

    public function registrosAsignacionesActivosFijos()
    {
        return $this->hasMany(AsignacionActivoFijo::class, 'usuario_id');
    }

    public function devolucionesAsignacionesActivosFijos()
    {
        return $this->hasMany(AsignacionActivoFijo::class, 'devuelto_a_id');
    }
}
