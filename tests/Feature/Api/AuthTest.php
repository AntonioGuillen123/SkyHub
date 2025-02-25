<?php

namespace Tests\Feature\Api;

use App\CreatePersonalAccessClient;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase, CreatePersonalAccessClient;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->createPersonalAccessClient();
    }

    private function authenticate(User $user, array $scopes = [])
    {
        Passport::actingAs(
            $user,
            $scopes
        );
    }

    public function test_CheckIfShowMyUserInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::find(1);

        $this->authenticate($user);

        $responseData = [
            'message' => 'User show successfully :)',
            'data' => [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'role_user' => [
                    'id' => 1,
                    'name' => 'admin',
                    'created_at' => $user->roleUser->created_at,
                    'updated_at' => $user->roleUser->updated_at
                ],
            ],
        ];

        $response = $this->getJson(route('apiShowUser'));

        $response
            ->assertStatus(200)
            ->assertJsonFragment($responseData);
    }

    public function test_CheckIfICanRegisterInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $requestData = [
            'name' => 'Name Test',
            'email' => 'example@example.com',
            'password' => 'P@ssw0rd',
            'password_confirmation' => 'P@ssw0rd',
        ];

        $responseData = [
            'message' =>  'User registered successfully. Check your inbox for verify email :)',
        ];

        $response = $this->postJson(route('apiRegister'), $requestData);
        
        $response
            ->assertStatus(201)
            ->assertJsonFragment($responseData);
    }
}
