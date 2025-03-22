<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(string $service){
        $socialiteProvider = $this->getSocialiteProviderFromService($service);

        $providerRedirect = $this->getProviderRedirect($socialiteProvider);

        return $providerRedirect;
    }

    public function callback(string $service){
        $socialiteProvider = $this->getSocialiteProviderFromService($service);

        $providerUser = $this->getProviderUser($socialiteProvider);

        return response()->json($providerUser);
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
