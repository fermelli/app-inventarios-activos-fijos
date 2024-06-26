<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro(
            'jsonResponse',
            /**
             * Return a new JSON response from the application.
             *
             * @param string $mensaje
             * @param \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Support\Collection|array|null $datos
             * @param int $codigoEstado
             */
            function (
                string $mensaje,
                Collection | Model | SupportCollection | array | null  $datos,
                int $codigoEstado
            ) {

                return Response::json([
                    'mensaje' => $mensaje,
                    'datos' => $datos,
                    'codigo_estado' => $codigoEstado,
                ], $codigoEstado);
            }
        );

        Response::macro(
            'jsonResponsePaginado',
            /**
             * Return a new JSON response from the application.
             *
             * @param string $mensaje
             * @param \Illuminate\Pagination\LengthAwarePaginator $paginador
             * @param int $codigoEstado
             */
            function (string $mensaje, LengthAwarePaginator $paginador, int $codigoEstado) {

                return Response::json([
                    'mensaje' => $mensaje,
                    'datos' => $paginador->items(),
                    'codigo_estado' => $codigoEstado,
                    'metadatos' => [
                        'pagina' => $paginador->currentPage(),
                        'items_por_pagina' => $paginador->perPage(),
                        'total' => $paginador->total(),
                        'ultima_pagina' => $paginador->lastPage(),
                    ],
                ], $codigoEstado);
            }
        );

        Response::macro(
            'jsonResponseError',
            /**
             * Return a new JSON response from the application.
             *
             * @param string $mensaje
             * @param int $codigoEstado
             */
            function (string $mensaje, int $codigoEstado) {

                return Response::json([
                    'mensaje' => $mensaje,
                    'datos' => null,
                    'codigo_estado' => $codigoEstado,
                ], $codigoEstado);
            }
        );

        Response::macro(
            'jsonResponseValidacionError',
            /**
             * Return a new JSON response from the application.
             *
             * @param string $mensaje
             * @param int $codigoEstado
             * @param array $errores
             */
            function (string $mensaje, int $codigoEstado, array $errores) {

                return Response::json([
                    'mensaje' => $mensaje,
                    'datos' => null,
                    'codigo_estado' => $codigoEstado,
                    'errores' => $errores,
                ], $codigoEstado);
            }
        );
    }
}
