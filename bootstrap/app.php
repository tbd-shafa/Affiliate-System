<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Http\Middleware\ReferralCookieMiddleware;
use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    // ->withMiddleware(function (Middleware $middleware) {
    //     //
    // })
    // ->withMiddleware(function (Middleware $middleware) {
    //     // Add an alias for the ReferralCookieMiddleware
    //     $middleware->alias([
    //         'referral.cookie' => ReferralCookieMiddleware::class,
    //     ]);
    // })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(ReferralCookieMiddleware::class);
        $middleware->alias([
                     'check.role' => CheckRole::class, // Alias for CheckRole middleware
                 ]);
    })

    // ->withMiddleware(function (Middleware $middleware) {
    //     $middleware->alias([
    //         'check.role' => CheckRole::class, // Alias for CheckRole middleware
    //     ]);
    // })


    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
