<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'page-cache'       => \App\Http\Middleware\PageCache::class,
            'permission'       => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'guard.employee'   => \App\Http\Middleware\SetEmployeeGuard::class,
            'redirect-old-car' => \App\Http\Middleware\RedirectOldCarSlug::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\LocalizationMiddleware::class,
        ]);

        $middleware->redirectTo(
            guests: function ($request) {
                if ($request->is('Souq-admin') || $request->is('Souq-admin/*')) {
                    return route('crm.login');
                }

                return route('new.home');
            }
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Not Found.'], 404);
            }

            return response()->view('errors.404', status: 404);
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }

            return response()->view('errors.403', status: 403);
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            $status = $e->getStatusCode();
            $view = match ($status) {
                403 => 'errors.403',
                419 => 'errors.419',
                429 => 'errors.429',
                500 => 'errors.500',
                503 => 'errors.503',
                default => null,
            };

            if ($view && ! $request->expectsJson()) {
                return response()->view($view, status: $status);
            }
        });
    })->create();
