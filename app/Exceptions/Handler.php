<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                if ($e instanceof AuthenticationException) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => 'Unauthenticated, silakan login terlebih dahulu',
                    ], 401);
                }

                if ($e instanceof ValidationException) {
                    return response()->json([
                        'status'  => 'error',
                        'message' => $e->getMessage(),
                        'errors'  => $e->errors(),
                    ], 422);
                }

                return response()->json([
                    'status'  => 'error',
                    'message' => $e->getMessage(),
                ], method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500);
            }
        });
    }
}