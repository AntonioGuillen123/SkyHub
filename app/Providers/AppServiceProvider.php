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
        $personalAccessTokenExpiration = intval(env('PASSPORT_PERSONAL_ACCESS_TOKEN_EXPIRATION', 15));

        Passport::personalAccessTokensExpireIn(now()->addDays($personalAccessTokenExpiration)); // Indica el tiempo de expiración de el Personal Access Token
        
        Passport::hashClientSecrets(); // Indica que se cifren los secretos de los clientes en la DB.

        Passport::tokensCan([ // Indica los permisos que existen en la aplicación
            'manage-airplanes'=> 'This scope allows manage airplanes',
            'manage-flights'=> 'This scope allows manage flights',
            'make-booking'=> 'This scope allows make a booking',
            'cancel-booking'=> 'This scope allows cancel a booking',
            'list-flights'=> 'This scope allows list flights',
            'list-bookings' => 'This scope allows list bookings',
            'list-all-bookings' => 'This scope allows list all users bookings'
        ]);

        Passport::setDefaultScope([ // Indica que permisos se asignan por defecto al crear el Personal Access Token
            'list-flights'
        ]);
    }
}
