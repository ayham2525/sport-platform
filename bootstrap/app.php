<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\EnsureUserIsNotPlayer;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\VerifyNfcToken;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Aliases for route middleware
        $middleware->alias([
            'not_player'  => EnsureUserIsNotPlayer::class,
            'set_locale'  => SetLocale::class,
            'nfc.token'   => VerifyNfcToken::class,
        ]);

        // Global middleware
        $middleware->append(SetLocale::class);
    })
     ->withCommands([
        __DIR__.'/../app/Domain/Orders/Commands',
    ])
    ->withExceptions(function (Exceptions $exceptions) {
        // Custom exception handling (optional)
    })
    ->create();
