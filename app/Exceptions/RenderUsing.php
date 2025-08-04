<?php

namespace App\Exceptions;

use Closure;
use Illuminate\Foundation\Exceptions\ShouldRenderExceptions;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

class RenderUsing implements ShouldRenderExceptions
{
    public function renderUsing(): Closure
    {
        return function (Throwable $e, $request) {
            if ($e instanceof MethodNotAllowedHttpException) {
                return response()->view('errors.method_not_allowed', [], 405);
            }

            return null; // Let Laravel handle all other exceptions
        };
    }
}
