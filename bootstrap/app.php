<?php

use App\Http\Middleware\CheckUserRole;
use App\UpdateFlightStatus;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Passport\Http\Middleware\CheckForAnyScope;
use Laravel\Passport\Http\Middleware\CheckScopes;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'scopes' => CheckScopes::class,
            'scope' => CheckForAnyScope::class,
            'checkRole' => CheckUserRole::class
        ]);
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->call(new UpdateFlightStatus)->everyFiveSeconds();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
