<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // 🔥 FIX AUTH REDIRECT (INI KUNCI NYA)
        $middleware->redirectGuestsTo(function () {
            return null; // jangan redirect ke route login
        });

    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // 🔥 HANDLE UNAUTHENTICATED JADI JSON
        $exceptions->render(function (AuthenticationException $e, $request) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 401);
        });

    })->create();