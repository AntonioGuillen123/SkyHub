<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Contracts\Provider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(string $provider)
    {
        $socialiteProvider = $this->getSocialiteProvider($provider);

        $providerRedirect = $this->getProviderRedirect($socialiteProvider);

        return $providerRedirect;
    }

    public function callback(string $provider)
    {
        $socialiteProvider = $this->getSocialiteProvider($provider);

        $userProvider = $this->getProviderUser($socialiteProvider);

        $userData = $this->getUserDataFromUserProvider($userProvider);

        $user = $this->getUserFromEmail($userData['email']);

        if (!$user) {
            $user = $this->createUser($userData);
        }

        $this->createOrUpdateAuthProviderFromUser($user, $userData, $provider);

        $this->authUser($user);

        return $this->responseWithSuccess();
    }

    private function getSocialiteProvider(string $provider)
    {
        return Socialite::driver($provider);
    }

    private function getProviderRedirect(Provider $provider)
    {
        return $provider->redirect();
    }

    private function getProviderUser(Provider $provider)
    {
        return $provider->stateless()->user(); // Se ha tenido que añadir el estateless aunque se menos seguro ya que Laravel funciona tan de maravilla que sin motivo alguno falla :))))))
    }

    private function getUserDataFromUserProvider(SocialiteUser $userProvider)
    {
        $providerId = $userProvider->getId();
        $providerEmail = $userProvider->getEmail();
        $providerNickname = $userProvider->getNickname();

        return [
            'provider_id' => $providerId,
            'email' => $providerEmail,
            'nickname' => $providerNickname ?? $this->generateNickname($providerEmail),
        ];
    }

    private function generateNickname(string $providerEmail)
    {
        return explode('@', $providerEmail)[0];
    }

    private function getUserFromEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    private function createUser(mixed $data)
    {
        return User::create([
            'email' => $data['email'],
            'name' => $data['nickname'],
            'email_verified_at' => now()
        ]);
    }

    private function createOrUpdateAuthProviderFromUser(User $user, mixed $data, string $provider)
    {
        $user->authProviders()->updateOrCreate([
            'provider_name' => $provider
        ], [
            'provider_id' => $data['provider_id'],
            'nickname' => $data['nickname'],
            'login_at' => now()
        ]);
    }

    private function authUser(User $user)
    {
        Auth::login($user);
    }

    private function responseWithSuccess()
    {
        return redirect(route('home'));
    }
}
