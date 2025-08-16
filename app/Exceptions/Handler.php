<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function register(): void
    {
        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return response()->view('errors.method_not_allowed', [], 405);
        });
    }

    /**
     * Handle unauthenticated users (session expired or not logged in).
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        // redirect to login instead of 500
        return redirect()->guest(route('login'));
    }
}
