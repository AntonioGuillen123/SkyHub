<?php

namespace Tests\Feature\Api;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FlightTest extends TestCase
{
    use RefreshDatabase;

    public function test_CheckIfRecieveAllEntriesOfFlightsInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->getJson(route('apiIndexFlight'));

        $response
            ->assertStatus(200)
            ->assertJsonCount(10);
    }

    public function test_CheckIfRecieveAnEntryOfFlightByIdInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->getJson(route('apiShowFlight', 1));

        $responseData = [
            'id' => 1,
            'airplane_id' => 1,
            'journey_id' => 1,
            'state' => 1,
            'remaining_places' => 420
        ];

        $response
            ->assertStatus(200)
            ->assertJsonFragment($responseData);
    }
}
