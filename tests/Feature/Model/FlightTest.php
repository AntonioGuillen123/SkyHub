<?php

namespace Tests\Feature\Model;

use App\Models\Flight;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FlightTest extends TestCase
{
    use RefreshDatabase;

    public function test_CheckIfFlightChangeStateWhenIsUpdating(){
        $this->seed(DatabaseSeeder::class);
        
        $flight = Flight::find(1);

        $flight->state = 0;
        $flight->remaining_places = 15;
        $flight->flight_date = now()->addYear(1)->format('Y-m-d H:i');
        $flight->updated_at = now()->addYear(1)->format('Y-m-d H:i');

        $flight->update();

        $this->assertTrue($flight->state);
    }

    public function test_CheckIfFlightIsAvailableWithPassedDate(){
        $this->seed(DatabaseSeeder::class);
        
        $flight = Flight::find(1);

        $flight->flight_date = now()->subYear(1)->format('Y-m-d H:i');

        $flight->update();

        $isAvailable = $flight->isAvailable();

        $this->assertFalse($isAvailable);
    }
}
