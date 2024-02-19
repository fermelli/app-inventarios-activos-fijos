<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e) {
            if (
                $e instanceof NotFoundHttpException
                || $e instanceof BadRequestHttpException
                || $e instanceof HttpException
            ) {
                return response()->jsonResponseError($e->getMessage(), $e->getStatusCode());
            }

            if ($e instanceof ValidationException) {
                return response()->jsonResponseValidacionError('Error de validación', $e->status, $e->errors());
            }

            if ($e instanceof InvalidArgumentException) {
                return response()->jsonResponseError($e->getMessage(), 500);
            }
        });
    }
}
