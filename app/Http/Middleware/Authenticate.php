<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        dd($request->expectsJson());
        if ($request->expectsJson()) {
            return url(env('APP_URL', 'http://localhost:8000') . '/auth/login');
        }

        return route('login');
    }
}
