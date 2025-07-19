<?php

use Illuminate\Http\Request;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsStudent;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'admin' => IsAdmin::class,
            'student' => IsStudent::class,
            'locale' => SetLocale::class,
        ]);

        
        // Global middleware
        $middleware->use([
            \Illuminate\Http\Middleware\TrustProxies::class,
            \Illuminate\Http\Middleware\HandleCors::class,
            SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->report(function (Throwable $e) {
            // Custom error reporting
        });
        
        $exceptions->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }
            return redirect()->guest(route('login'));
        });
    })
    ->create();