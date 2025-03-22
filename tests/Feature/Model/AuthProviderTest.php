<?php

namespace Tests\Feature\Model;

use App\Models\AuthProvider;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthProviderTest extends TestCase
{
    use RefreshDatabase;

    public function test_CheckIfAuthProviderHasUserRelationship()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::find(1);

        $authProvider = AuthProvider::create([
            'user_id' => $user->id,
            'provider_id' => '123456789',
            'provider_name' => 'github',
            'nickname' => 'testNickname',
            'login_at' => now(),
        ]);

        $this->assertInstanceOf(User::class, $authProvider->user);
    }
}
