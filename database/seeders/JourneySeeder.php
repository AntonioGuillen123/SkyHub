<?php

namespace Database\Seeders;

use App\Models\Journey;
use Illuminate\Database\Seeder;

class JourneySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $journeys = [
            ['departure_id' => 1, 'arrival_id' => 2],
            ['departure_id' => 1, 'arrival_id' => 3],
            ['departure_id' => 1, 'arrival_id' => 4],
            ['departure_id' => 1, 'arrival_id' => 5],
            ['departure_id' => 2, 'arrival_id' => 6],
            ['departure_id' => 2, 'arrival_id' => 7],
            ['departure_id' => 2, 'arrival_id' => 8],
            ['departure_id' => 3, 'arrival_id' => 9],
            ['departure_id' => 3, 'arrival_id' => 10],
            ['departure_id' => 4, 'arrival_id' => 6],
            ['departure_id' => 4, 'arrival_id' => 7],
            ['departure_id' => 5, 'arrival_id' => 8],
            ['departure_id' => 5, 'arrival_id' => 9],
            ['departure_id' => 6, 'arrival_id' => 10],
            ['departure_id' => 6, 'arrival_id' => 1],
            ['departure_id' => 7, 'arrival_id' => 3],
            ['departure_id' => 7, 'arrival_id' => 5],
            ['departure_id' => 8, 'arrival_id' => 2],
            ['departure_id' => 8, 'arrival_id' => 4],
            ['departure_id' => 9, 'arrival_id' => 10]
        ];

        foreach ($journeys as $journey) {
            Journey::create($journey);
        }
    }
}
