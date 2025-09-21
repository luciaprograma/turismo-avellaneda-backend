<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ThrottleRequestsException) {
            return response()->json([
                'status' => 'error',
                'code' => 'too_many_requests',
                'message' => 'Demasiadas peticiones. Por favor, intentá de nuevo más tarde.'
            ], 429);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'status' => 'error',
                'code' => 'not_found',
                'message' => 'La ruta solicitada no existe.'
            ], 404);
        }

        if ($exception instanceof ValidationException) {
            return $this->invalidJson($request, $exception);
        }

        return response()->json([
            'status' => 'error',
            'code' => 'internal_server_error',
            'message' => 'Ocurrió un error inesperado en el servidor.'
        ], 500);
    }

    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'status'  => 'error',
            'code'    => 'validation_error',
            'message' => 'Hay campos que no pasaron la validación.',
            'errors'  => collect($exception->errors())->map(fn($e) => $e[0]),
        ], 422);
    }
}
