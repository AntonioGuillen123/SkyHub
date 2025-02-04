<?php

namespace Tests\Feature\Api;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AirplaneTest extends TestCase
{
    use RefreshDatabase;

    public function test_CheckIfRecieveAllEntriesOfAirplanesInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $response = $this->getJson(route('apiIndexAirplane'));

        $response
            ->assertStatus(200)
            ->assertJsonCount(10);
    }

    public function test_CheckIfRecieveAnEntryOfAirplaneByIdInJsonFile(){
        $this->seed(DatabaseSeeder::class);

        $response = $this->getJson(route('apiShowAirplane', 1));

        $responseData = [
            'id' => 1,
            'name' => 'Boeing 747',
            'maximum_places' => 420
        ];

        $response
            ->assertStatus(200)
            ->assertJsonFragment($responseData);
    }

    public function test_CheckIfRecieveAnEntryOfAirplaneWrongByIdInJsonFile(){
        $this->seed(DatabaseSeeder::class);

        $response = $this->getJson(route('apiShowAirplane', -1));

        $errorData = [
            'message' => 'The airplane id does not exist :('
        ];

        $response
            ->assertStatus(404)
            ->assertJsonFragment($errorData);
    }

    public function test_CheckIfPostAnEntryOfAirplaneInJsonFile(){
        $this->seed(DatabaseSeeder::class);

        $data = [
            'name' => 'Test Airplane',
            'maximum_places' => 999
        ];

        $response = $this->postJson(route('apiStoreAirplane'), $data);

        $response
            ->assertStatus(201)
            ->assertJsonFragment($data);
    }
}
