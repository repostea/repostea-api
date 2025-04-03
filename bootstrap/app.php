<?php

use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\CheckApiKey;
use App\Http\Middleware\CheckMinimumKarma;
use App\Http\Middleware\ModeratorOnly;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => AdminOnly::class,
            'moderator' => ModeratorOnly::class,
            'min.karma' => CheckMinimumKarma::class,
            'check.api.key' => CheckApiKey::class,
        ]);

        $middleware->group('api', [
            CheckApiKey::class,
        ]);

        $middleware->append([
            SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Not found'], 404);
            }
            if ($request->is('admin/*')) {
                return response()->view('admin.errors.404', [], 404);
            }

            return response()->view('errors.404', [], 404);
        });
    })->create();
