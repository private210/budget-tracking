<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // temporary: surface real error text in browser for Vercel diagnostics
        $exceptions->renderable(function (Throwable $e, $request) {
            return response($e->getMessage()."\n\n".$e->getFile().':'.$e->getLine(), 500);
        });
    })->create();
