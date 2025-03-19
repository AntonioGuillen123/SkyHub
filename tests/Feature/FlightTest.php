<?php

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FlightTest extends TestCase
{
    use RefreshDatabase;

    public function test_CheckIfFlightViewIsLoaded(): void
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->get(route('indexFlight'));

        $response->assertStatus(200);
    }
}
