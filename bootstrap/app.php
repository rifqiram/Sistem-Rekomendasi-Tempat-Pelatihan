<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\QueryException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.token' => \App\Http\Middleware\SystemAuth::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Validation Error (422)
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal.',
                    'errors' => $e->errors(),
                ], 422);
            }
        });

        // Authentication Error (401)
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                ], 401);
            }
        });

        // Authorization Error (403)
        $exceptions->render(function (AuthorizationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized / Forbidden.',
                ], 403);
            }
        });

        // Model Not Found (404)
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $modelName = class_basename($e->getModel());
                return response()->json([
                    'success' => false,
                    'message' => "{$modelName} tidak ditemukan.",
                ], 404);
            }
        });

        // Route Not Found (404)
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Endpoint tidak ditemukan.',
                ], 404);
            }
        });

        // Database Query Error (500)
        $exceptions->render(function (QueryException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                // Di production, jangan expose query sebenarnya.
                $message = config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan pada database.';
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 500);
            }
        });

        // Fallback General Exception (500)
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $message = config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan pada server.';

                // Menghindari intercept 404/403 bawaan Laravel yang belum ter-catch di atas
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

                // Jika status bukan 500 dan message kosong, beri default fallback
                if ($statusCode !== 500 && empty($message)) {
                     $message = 'Request tidak dapat diproses.';
                }

                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], $statusCode);
            }
        });

    })->create();
