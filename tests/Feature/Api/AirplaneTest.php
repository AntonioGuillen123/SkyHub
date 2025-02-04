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
}
