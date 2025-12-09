<?php

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
        $middleware->alias([
            // ✅ alias lama boleh tetap
            'assessment'        => \App\Http\Middleware\EnsureAssessmentLogin::class,

            // ✅ alias baru untuk auto-isi session pelatihan/kompetensi
            'training.session'  => \App\Http\Middleware\EnsureActiveTrainingSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
