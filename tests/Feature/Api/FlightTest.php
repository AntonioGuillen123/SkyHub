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

    public function test_CheckIfRecieveAnEntryOfFlightWrongByIdInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->getJson(route('apiShowFlight', -1));

        $errorData = [
            'message' => 'The flight id does not exist :('
        ];

        $response
            ->assertStatus(404)
            ->assertJsonFragment($errorData);
    }

    public function test_CheckIfPostAnEntryOfFlightInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $data = [
            'airplane_id' => 1,
            'journey_id' => 1,
            'state' => 0,
            'remaining_places' => 999
        ];

        $response = $this->postJson(route('apiStoreFlight'), $data);

        $response
            ->assertStatus(201)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfPostAnEntryOfFlightWrongInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $data = [
            'airplane_id' => 99999,
            'journey_id' => 1,
            'state' => 0,
            'remaining_places' => 999
        ];

        $response = $this->postJson(route('apiStoreFlight'), $data);

        $errorData = [
            'message' => 'The airplane id does not exist :('
        ];

        $response
            ->assertStatus(404)
            ->assertJsonFragment($errorData);

        $data = [
            'airplane_id' => 1,
            'journey_id' => 99999,
            'state' => 0,
            'remaining_places' => 999
        ];

        $response = $this->postJson(route('apiStoreFlight'), $data);

        $errorData = [
            'message' => 'The journey id does not exist :('
        ];

        $response
            ->assertStatus(404)
            ->assertJsonFragment($errorData);
    }

    public function test_CheckIfUpdateAnEntryOfFlightByIdInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $data = [
            'airplane_id' => 2,
            'journey_id' => 2,
            'state' => 0,
            'remaining_places' => 999
        ];

        $response = $this->putJson(route('apiUpdateFlight', 1), $data);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfUpdateAnEntryOfFlightWrongInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $data = [
            'airplane_id' => 1,
            'journey_id' => 1,
            'state' => 0,
            'remaining_places' => 999
        ];

        $response = $this->putJson(route('apiUpdateFlight', -1), $data);

        $errorData = [
            'message' => 'The flight id does not exist :('
        ];

        $response
            ->assertStatus(404)
            ->assertJsonFragment($errorData);

        $data = [
            'airplane_id' => 99999,
            'journey_id' => 1,
            'state' => 0,
            'remaining_places' => 999
        ];

        $response = $this->putJson(route('apiUpdateFlight', 1), $data);

        $errorData = [
            'message' => 'The airplane id does not exist :('
        ];

        $response
            ->assertStatus(404)
            ->assertJsonFragment($errorData);

        $data = [
            'airplane_id' => 1,
            'journey_id' => 99999,
            'state' => 0,
            'remaining_places' => 999
        ];

        $response = $this->putJson(route('apiUpdateFlight', 1), $data);

        $errorData = [
            'message' => 'The journey id does not exist :('
        ];

        $response
            ->assertStatus(404)
            ->assertJsonFragment($errorData);
    }

    public function test_CheckIfDeleteAnEntryOfFlightByIdInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->deleteJson(route('apiDestroyFlight', 1));

        $response
            ->assertStatus(204);
    }

    public function test_CheckIfDeleteAnEntryOfAirplaneWrongByIdInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->deleteJson(route('apiDestroyFlight', -1));

        $errorData = [
            'message' => 'The flight id does not exist :('
        ];

        $response
            ->assertStatus(404)
            ->assertJsonFragment($errorData);
    }
}
