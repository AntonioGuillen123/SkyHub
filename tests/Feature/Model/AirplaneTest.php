<?php

namespace Tests\Feature\Model;

use App\Models\Airplane;
use App\Models\Journey;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AirplaneTest extends TestCase
{
    use RefreshDatabase;
   
    public function test_CheckIfAirplaneHasJourneysRelationship(){
        $this->seed(DatabaseSeeder::class);

        $airplane = Airplane::find(1);

        $this->assertInstanceOf(Journey::class, $airplane->journeys[0]);
    }
}
