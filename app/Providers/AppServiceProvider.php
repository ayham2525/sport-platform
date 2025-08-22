<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\AuthenticationException;
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->make(ExceptionHandler::class)
            ->renderable(function (Throwable $e, $request) {

                if ($e instanceof MethodNotAllowedHttpException) {
                    return response()->view('errors.method_not_allowed', [], 405);
                }

                if ($e instanceof NotFoundHttpException) {
                    return response()->view('errors.not_found', [], 404);
                }

                if ($e instanceof AuthorizationException) {
                    return response()->view('errors.unauthorized', [], 403);
                }

                if ($e instanceof ValidationException) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'message' => 'Validation failed.',
                            'errors' => $e->errors()
                        ], 422);
                    }

                    return redirect()->back()
                        ->withErrors($e->errors())
                        ->withInput();
                }

                // 👇 Handle session expired / unauthenticated
                if ($e instanceof AuthenticationException) {
                    return redirect()->guest(route('login'));
                }

                if ($e instanceof HttpException || $e instanceof \Error || $e instanceof \Exception) {
                    return response()->view('errors.server_error', [], 500);
                }

                return null; // fallback to default handler
            });
    }
}
