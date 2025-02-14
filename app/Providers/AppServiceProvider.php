<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::hashClientSecrets();

        Passport::tokensCan([
            'manage-airplanes'=> 'This scope allows manage airplanes',
            'manage-flights'=> 'This scope allows manage flights',
            'make-booking'=> 'This scope allows make a booking',
            'list-flights'=> 'This scope allows list flights',
            'list-bookings' => 'This scope allows list bookings',
            'list-all-bookings' => 'This scope allows list all users bookings'
        ]);
    }
}
