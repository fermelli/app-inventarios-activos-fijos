<?php

use App\Http\Controllers\ActivoFijoController;
use App\Http\Controllers\ActivoFijoExcelController;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\ArticuloReportePdfController;
use App\Http\Controllers\ArticulosExcelController;
use App\Http\Controllers\AsignacionActivoFijoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EntradaArticuloController;
use App\Http\Controllers\EntradaArticuloExcelController;
use App\Http\Controllers\EntradaArticuloReportePdfController;
use App\Http\Controllers\InstitucionController;
use App\Http\Controllers\SalidaArticuloController;
use App\Http\Controllers\SalidaArticuloReportePdfController;
use App\Http\Controllers\SolicitudArticuloController;
use App\Http\Controllers\SolicitudArticuloReportePdfController;
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
        return response()->jsonResponse('Datos del usuario autenticado.', $request->user(), 200);
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
        Route::post('articulos/importar', [ArticulosExcelController::class, 'importar']);
        Route::get('articulos/formato-importacion', [ArticulosExcelController::class, 'formatoImportacion']);
        Route::get('articulos/exportar', [ArticulosExcelController::class, 'exportar']);
        Route::get('articulos/reporte-pdf', [ArticuloReportePdfController::class, 'index']);
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
    Route::apiResource('usuarios', UsuarioController::class, ['only' => ['index', 'show', 'update']]);

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
        Route::match(
            ['put', 'patch'],
            'entradas-articulos/{entradaArticulo}/anular',
            [EntradaArticuloController::class, 'anular']
        );
        Route::post('entradas-articulos/importar', [EntradaArticuloExcelController::class, 'importar']);
        Route::get(
            'entradas-articulos/formato-importacion',
            [EntradaArticuloExcelController::class, 'formatoImportacion']
        );
        Route::apiResource(
            'entradas-articulos',
            EntradaArticuloController::class,
            ['only' => ['index', 'store', 'show']]
        )->parameters(['entradas-articulos' => 'entradaArticulo']);
        Route::get(
            'entradas-articulos/{entradaArticulo}/reporte-pdf',
            [EntradaArticuloReportePdfController::class, 'show']
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
    Route::get(
        'solicitudes-articulos/{solicitudArticulo}/reporte-pdf',
        [SolicitudArticuloReportePdfController::class, 'show']
    );
    Route::middleware(['can:' . User::ROL_ADMINISTRADOR])->group(function () {
        Route::apiResource(
            'solicitudes-articulos',
            SolicitudArticuloController::class,
            ['only' => ['index']]
        );

        Route::post(
            'solicitudes-articulos/solicitante',
            [SolicitudArticuloController::class, 'storeConSolicitante']
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
    Route::get(
        'salidas-articulos/{salidaArticulo}/reporte-pdf',
        [SalidaArticuloReportePdfController::class, 'show']
    );
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

    // Rutas para dashboard
    Route::middleware(['can:' . User::ROL_ADMINISTRADOR])->group(function () {
        Route::get(
            'dashboard/cantidad-registros',
            [DashboardController::class, 'cantidadRegistros']
        );
        Route::get(
            'dashboard/articulos-con-stock-minimo',
            [DashboardController::class, 'articulosConStockMinimo']
        );
        Route::get(
            'dashboard/articulos-recientemente-vencidos',
            [DashboardController::class, 'articulosRecientementeVencidos']
        );
        Route::get(
            'dashboard/articulos-proximos-vencer',
            [DashboardController::class, 'articulosProximosVencer']
        );
    });

    // Rutas para Activos Fijos
    Route::middleware(['can:' . User::ROL_ADMINISTRADOR])->group(function () {
        Route::post('activos-fijos/importar', [ActivoFijoExcelController::class, 'importar']);
        Route::get('activos-fijos/formato-importacion', [ActivoFijoExcelController::class, 'formatoImportacion']);
        Route::apiResource(
            'activos-fijos',
            ActivoFijoController::class
        )->parameters(['activos-fijos' => 'activoFijo']);
        Route::match(
            ['put', 'patch'],
            'activos-fijos/{activoFijo}/dar-baja',
            [ActivoFijoController::class, 'darBaja']
        );
    });

    // Rutas para Asignaciones de Activos Fijos
    Route::middleware(['can:' . User::ROL_ADMINISTRADOR])->group(function () {
        Route::apiResource(
            'asignaciones-activos-fijos',
            AsignacionActivoFijoController::class
        )
        ->only(['index', 'store', 'show'])
        ->parameters(['asignaciones-activos-fijos' => 'asignacionActivoFijo']);
        Route::match(
            ['put', 'patch'],
            'asignaciones-activos-fijos/{asignacionActivoFijo}/devolver',
            [AsignacionActivoFijoController::class, 'devolver']
        );
    });
});
