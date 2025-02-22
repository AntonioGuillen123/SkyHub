<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AirplaneTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate(){
        $user = User::find(1);

        Passport::actingAs(
            $user,
            ['manage-airplanes']
        );
    }

    public function test_CheckIfRecieveAllEntriesOfAirplanesInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate();

        $response = $this->getJson(route('apiIndexAirplane'));

        $response
            ->assertStatus(200)
            ->assertJsonCount(10);
    }

    public function test_CheckIfRecieveAnEntryOfAirplaneByIdInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate();

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

    public function test_CheckIfRecieveAnEntryOfAirplaneWrongByIdInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate();

        $response = $this->getJson(route('apiShowAirplane', -1));

        $errorData = [
            'message' => 'The airplane id does not exist :('
        ];

        $response
            ->assertStatus(404)
            ->assertJsonFragment($errorData);
    }

    public function test_CheckIfPostAnEntryOfAirplaneInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate();

        $data = [
            'name' => 'Test Airplane',
            'maximum_places' => 999
        ];

        $response = $this->postJson(route('apiStoreAirplane'), $data);

        $response
            ->assertStatus(201)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfUpdateAnEntryOfAirplaneByIdInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate();

        $data = [
            'name' => 'Test Airplane Updated',
            'maximum_places' => 999
        ];

        $response = $this->putJson(route('apiUpdateAirplane', 1), $data);

        $response
            ->assertStatus(200)
            ->assertJsonFragment($data);
    }

    public function test_CheckIfUpdateAnEntryOfAirplaneWrongByIdInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate();

        $data = [
            'name' => 'Test Airplane Updated',
            'maximum_places' => 999
        ];

        $response = $this->putJson(route('apiUpdateAirplane', -1), $data);

        $errorData = [
            'message' => 'The airplane id does not exist :('
        ];

        $response
            ->assertStatus(404)
            ->assertJsonFragment($errorData);
    }

    public function test_CheckIfDeleteAnEntryOfAirplaneByIdInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate();

        $response = $this->deleteJson(route('apiDestroyAirplane', 1));

        $response
            ->assertStatus(204);
    }

    public function test_CheckIfDeleteAnEntryOfAirplaneWrongByIdInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate();

        $response = $this->deleteJson(route('apiDestroyAirplane', -1));

        $errorData = [
            'message' => 'The airplane id does not exist :('
        ];

        $response
            ->assertStatus(404)
            ->assertJsonFragment($errorData);
    }
}
