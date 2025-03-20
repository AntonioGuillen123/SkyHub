<?php

namespace Tests\Feature\Model;

use App\Models\Airplane;
use App\Models\Journey;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class JourneyTest extends TestCase
{
    use RefreshDatabase;

    public function test_CheckIfJourneyHasAirplanesRelationship(){
        $this->seed(DatabaseSeeder::class);

        $journey = Journey::find(1);

        $this->assertInstanceOf(Airplane::class, $journey->airplanes[0]);
    }
}
