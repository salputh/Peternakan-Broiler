<?php

use App\Http\Middleware\EnsureKandangExist;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1) alias supaya bisa dipakai di route:
        $middleware->alias([
            'ensure.kandang' => EnsureKandangExist::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // 2) global middleware (append ke semua request):
        $middleware->append(EnsureKandangExist::class);

        // 3) atau tambahin hanya di 'web' group:
        $middleware->appendToGroup('web', EnsureKandangExist::class);
    })
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

    // ...
    //...
