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
}
