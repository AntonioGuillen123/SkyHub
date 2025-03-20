<?php

namespace Tests\Feature\Model;

use App\Models\Destination;
use App\Models\Journey;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DestinationTest extends TestCase
{
    use RefreshDatabase;
   
    public function test_CheckIfDestinationHasJourneyDeparturesRelationship(){
        $this->seed(DatabaseSeeder::class);

        $destination = Destination::find(1);

        $this->assertInstanceOf(Journey::class, $destination->journeyDepartures[0]);
    }

    public function test_CheckIfDestinationHasJourneyArrivalsRelationship(){
        $this->seed(DatabaseSeeder::class);

        $destination = Destination::find(1);

        $this->assertInstanceOf(Journey::class, $destination->journeyArrivals[0]);
    }
}
