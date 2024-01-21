<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define(User::ROL_ADMINISTRADOR, function (User $usuario) {
            return $usuario->rol == User::ROL_ADMINISTRADOR
                                ? Response::allow()
                                : Response::denyWithStatus(403, 'No tienes permiso para crear categorias');
        });
    }
}
