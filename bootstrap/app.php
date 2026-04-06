<?php

use App\Exceptions\ApiExceptionHandler;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trimStrings(except: [
            'password',
            'password_confirmation',
            'data.attributes.password',
            'data.attributes.password_confirmation',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            if ($request->is('api/*')) {
                return true;
            }
            return $request->expectsJson();
        });

        $exceptions->render([ApiExceptionHandler::class, 'ModelNotFoundHandler']);
        $exceptions->render([ApiExceptionHandler::class, 'ValidationExceptionHandler']);
        $exceptions->render([ApiExceptionHandler::class, 'AuthenticationExceptionHandler']);

        $exceptions->render([ApiExceptionHandler::class, 'GeneralExceptionHandler']);
    })->create();
