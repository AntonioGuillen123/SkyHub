<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AirplaneTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate(User $user)
    {
        $this->actingAs($user);
    }

    public function test_CheckIfAirplaneViewIsLoaded(): void
    {
        $this->seed(DatabaseSeeder::class);

        $user = User::find(1);

        $this->authenticate($user);
        
        $response = $this->get(route('indexAirplane'));

        $response->assertStatus(200);
    }

    public function test_CheckIfAirplaneViewIsRedirectWithUnauthenticated(): void
    {
        $this->seed(DatabaseSeeder::class);
        
        $response = $this->get(route('indexAirplane'));

        $response->assertStatus(302);
    }
}
