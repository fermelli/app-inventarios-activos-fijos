<?php

use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\EntradaArticuloController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\SalidaArticuloController;
use App\Http\Controllers\SolicitudArticuloController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\UnidadController;
use App\Http\Controllers\UsuarioController;
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

    // Rutas para unidades
    Route::middleware(['can:' . User::ROL_ADMINISTRADOR])->group(function () {
        Route::apiResource(
            'unidades',
            UnidadController::class,
            ['only' => ['store', 'update', 'destroy']]
        )->parameters(['unidades' => 'unidad']);
    });
    Route::apiResource('unidades', UnidadController::class, ['only' => ['index', 'show']])
                                            ->parameters(['unidades' => 'unidad']);

    // Rutas para ubicaciones
    Route::middleware(['can:' . User::ROL_ADMINISTRADOR])->group(function () {
        Route::apiResource(
            'ubicaciones',
            UbicacionController::class,
            ['only' => ['store', 'update', 'destroy']]
        )->parameters(['ubicaciones' => 'ubicacion']);
    });
    Route::apiResource('ubicaciones', UbicacionController::class, ['only' => ['index', 'show']])
                                            ->parameters(['ubicaciones' => 'ubicacion']);

    // Rutas para articulos
    Route::middleware(['can:' . User::ROL_ADMINISTRADOR])->group(function () {
        Route::match(
            ['put', 'patch'],
            'articulos/{articulo}/desactivar',
            [ArticuloController::class, 'softDestroy']
        );
        Route::match(
            ['put', 'patch'],
            'articulos/{articulo}/activar',
            [ArticuloController::class, 'restore']
        );
        Route::apiResource(
            'articulos',
            ArticuloController::class,
            ['only' => ['store', 'update', 'destroy']]
        );
    });
    Route::apiResource('articulos', ArticuloController::class, ['only' => ['index', 'show']]);

    // Rutas para instituciones
    Route::middleware(['can:' . User::ROL_ADMINISTRADOR])->group(function () {
        Route::match(
            ['put', 'patch'],
            'instituciones/{institucion}/desactivar',
            [InstitucionController::class, 'softDestroy']
        );
        Route::match(
            ['put', 'patch'],
            'instituciones/{institucion}/activar',
            [InstitucionController::class, 'restore']
        );
        Route::apiResource(
            'instituciones',
            InstitucionController::class,
            ['only' => ['store', 'update', 'destroy']]
        )->parameters(['instituciones' => 'institucion']);
    });
    Route::apiResource('instituciones', InstitucionController::class, ['only' => ['index', 'show']])
                                            ->parameters(['instituciones' => 'institucion']);

    // Rutas para usuarios
    Route::middleware(['can:' . User::ROL_ADMINISTRADOR])->group(function () {
        Route::match(
            ['put', 'patch'],
            'usuarios/{usuario}/desactivar',
            [UsuarioController::class, 'softDestroy']
        );
        Route::match(
            ['put', 'patch'],
            'usuarios/{usuario}/activar',
            [UsuarioController::class, 'restore']
        );
        Route::match(
            ['put', 'patch'],
            'usuarios/{usuario}/cambiar-rol',
            [UsuarioController::class, 'cambiarRol']
        );
        Route::apiResource(
            'usuarios',
            UsuarioController::class,
            ['only' => ['destroy']]
        );
    });
    Route::apiResource('usuarios', UsuarioController::class, ['only' => ['index', 'show']]);

    // Rutas para entradas de articulos
    Route::middleware(['can:' . User::ROL_ADMINISTRADOR])->group(function () {
        Route::match(
            ['put', 'patch'],
            'entradas-articulos/{entradaArticulo}/desactivar',
            [EntradaArticuloController::class, 'softDestroy']
        );
        Route::match(
            ['put', 'patch'],
            'entradas-articulos/{entradaArticulo}/activar',
            [EntradaArticuloController::class, 'restore']
        );
        Route::apiResource(
            'entradas-articulos',
            EntradaArticuloController::class,
            ['only' => ['index', 'store']]
        );
    });

    // Rutas para solicitudes de articulos
    Route::get(
        'solicitudes-articulos/usuario',
        [SolicitudArticuloController::class, 'indexUsuario']
    );
    Route::apiResource(
        'solicitudes-articulos',
        SolicitudArticuloController::class,
        ['only' => ['show']]
    )->parameters(['solicitudes-articulos' => 'solicitudArticulo']);
    Route::middleware(['can:' . User::ROL_ADMINISTRADOR])->group(function () {
        Route::apiResource(
            'solicitudes-articulos',
            SolicitudArticuloController::class,
            ['only' => ['index']]
        );
    });
    Route::match(
        ['put', 'patch'],
        'solicitudes-articulos/{solicitudArticulo}/desactivar',
        [SolicitudArticuloController::class, 'softDestroy']
    );
    Route::match(
        ['put', 'patch'],
        'solicitudes-articulos/{solicitudArticulo}/activar',
        [SolicitudArticuloController::class, 'restore']
    );
    Route::apiResource(
        'solicitudes-articulos',
        SolicitudArticuloController::class,
        ['only' => ['store']]
    );

    // Rutas para salidas de articulos
    Route::get(
        'salidas-articulos/usuario',
        [SalidaArticuloController::class, 'indexUsuario']
    );
    Route::apiResource(
        'salidas-articulos',
        SalidaArticuloController::class,
        ['only' => ['show']]
    )->parameters(['salidas-articulos' => 'salidaArticulo']);
    Route::middleware(['can:' . User::ROL_ADMINISTRADOR])->group(function () {
        Route::match(
            ['put', 'patch'],
            'salidas-articulos/{salidaArticulo}/aprobar',
            [SalidaArticuloController::class, 'aprobar']
        );
        Route::match(
            ['put', 'patch'],
            'salidas-articulos/{salidaArticulo}/rechazar',
            [SalidaArticuloController::class, 'rechazar']
        );
        Route::match(
            ['put', 'patch'],
            'salidas-articulos/{salidaArticulo}/entregar',
            [SalidaArticuloController::class, 'entregar']
        );
        Route::match(
            ['put', 'patch'],
            'salidas-articulos/{salidaArticulo}/anular',
            [SalidaArticuloController::class, 'anular']
        );
        Route::apiResource(
            'salidas-articulos',
            SalidaArticuloController::class,
            ['only' => ['index']]
        );
    });
});
