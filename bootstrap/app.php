<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\EnsureUserIsNotPlayer;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // Alias for custom middlewares
        $middleware->alias([
            'not_player' => EnsureUserIsNotPlayer::class,
             'set_locale' => SetLocale::class,
        ]);
        $middleware->append(SetLocale::class);
             
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Custom exception handling (if needed)
    })
    ->create();
