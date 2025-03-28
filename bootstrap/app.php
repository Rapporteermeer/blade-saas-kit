<?php

use App\Http\Middleware\EnsureHasTeam;
use App\Http\Middleware\ProcessInvitationAfterLogin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureUserHasSubscription;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Stripe middleware voorkomt CSRF verplichtingen in stripe.
        $middleware->validateCsrfTokens(except: [
            'stripe/*',
        ]);

        $middleware->append(ProcessInvitationAfterLogin::class);
        $middleware->append(EnsureHasTeam::class);
        $middleware->append(EnsureUserHasSubscription::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
