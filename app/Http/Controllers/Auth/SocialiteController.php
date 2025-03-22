<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(string $service){
        $socialiteProvider = $this->getSocialiteProviderFromService($service);

        $providerRedirect = $this->getProviderRedirect($socialiteProvider);

        return $providerRedirect;
    }

    private function getSocialiteProviderFromService(string $service){
        return Socialite::driver($service);
    }

    private function getProviderRedirect(Provider $provider){
        return $provider->redirect();
    }

    private function getProviderUser(Provider $provider){
        return $provider->user();
    }
}
