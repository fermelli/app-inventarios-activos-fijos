<?php

use App\Http\Controllers\CategoriaController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/usuario-autenticado', function (Request $request) {
        return $request->user();
    });

    // Rutas para categorias
    Route::get(
        'categorias/padres-con-hijas',
        [CategoriaController::class, 'indexPadresConHijas']
    );
    Route::middleware(['can:' . User::ROL_ADMINISTRADOR])->group(function () {
        Route::match(
            ['put', 'patch'],
            'categorias/{categoria}/desactivar',
            [CategoriaController::class, 'softDestroy']
        );
        Route::match(
            ['put', 'patch'],
            'categorias/{categoria}/activar',
            [CategoriaController::class, 'restore']
        );
        Route::apiResource(
            'categorias',
            CategoriaController::class,
            ['only' => ['store', 'update', 'destroy']]
        );
    });
    Route::apiResource('categorias', CategoriaController::class, ['only' => ['index', 'show']]);
});
