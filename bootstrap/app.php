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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'super.admin' => \App\Http\Middleware\IsSuperAdmin::class,
            'hr' => \App\Http\Middleware\IsHR::class,
            'interviewer' => \App\Http\Middleware\IsInterviewer::class,
            'candidate' => \App\Http\Middleware\IsCandidate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
