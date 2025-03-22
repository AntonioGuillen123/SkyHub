<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(string $provider){
        return Socialite::driver($provider)->redirect();
    }
}
