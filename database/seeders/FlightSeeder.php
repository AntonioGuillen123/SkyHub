<?php

namespace Database\Seeders;

use App\Models\Flight;
use Illuminate\Database\Seeder;

class FlightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $flights = [
            ['airplane_id' => 1, 'journey_id' => 1, 'state' => 1],
            ['airplane_id' => 2, 'journey_id' => 2, 'state' => 1],
            ['airplane_id' => 3, 'journey_id' => 3, 'state' => 0],
            ['airplane_id' => 1, 'journey_id' => 4, 'state' => 1],
            ['airplane_id' => 2, 'journey_id' => 5, 'state' => 1],
            ['airplane_id' => 3, 'journey_id' => 6, 'state' => 0],
            ['airplane_id' => 1, 'journey_id' => 7, 'state' => 1],
            ['airplane_id' => 2, 'journey_id' => 8, 'state' => 1],
            ['airplane_id' => 3, 'journey_id' => 9, 'state' => 0],
            ['airplane_id' => 1, 'journey_id' => 10, 'state' => 1],
        ];

        foreach ($flights as $flight) {
            Flight::create($flight);
        }
    }
}
