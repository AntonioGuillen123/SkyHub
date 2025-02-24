<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthTest extends TestCase
{
    private function authenticate(array $scopes = [])
    {
        $user = User::find(1);

        Passport::actingAs(
            $user,
            $scopes
        );
    }
}
