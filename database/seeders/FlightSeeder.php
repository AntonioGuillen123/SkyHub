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
        $futureDate = date('Y-m-d H:i:s', strtotime('+6 months'));
        $pastDate = date('Y-m-d H:i:s', strtotime('-2 months'));

        $flights = [
            ['airplane_id' => 1, 'journey_id' => 1, 'state' => 1, 'price' => 120, 'remaining_places' => 400, 'flight_date' => $futureDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($futureDate . ' +2 hours'))],
            ['airplane_id' => 2, 'journey_id' => 2, 'state' => 1, 'price' => 200, 'remaining_places' => 150, 'flight_date' => $futureDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($futureDate . ' +3 hours'))],
            ['airplane_id' => 3, 'journey_id' => 3, 'state' => 1, 'price' => 150, 'remaining_places' => 100, 'flight_date' => $futureDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($futureDate . ' +4 hours'))],
            ['airplane_id' => 4, 'journey_id' => 4, 'state' => 1, 'price' => 170, 'remaining_places' => 380, 'flight_date' => $futureDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($futureDate . ' +1.5 hours'))],
            ['airplane_id' => 5, 'journey_id' => 5, 'state' => 1, 'price' => 220, 'remaining_places' => 600, 'flight_date' => $futureDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($futureDate . ' +2.5 hours'))],
            ['airplane_id' => 6, 'journey_id' => 6, 'state' => 1, 'price' => 180, 'remaining_places' => 350, 'flight_date' => $futureDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($futureDate . ' +3 hours'))],
            ['airplane_id' => 7, 'journey_id' => 7, 'state' => 1, 'price' => 140, 'remaining_places' => 250, 'flight_date' => $futureDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($futureDate . ' +2 hours'))],
            ['airplane_id' => 8, 'journey_id' => 8, 'state' => 1, 'price' => 160, 'remaining_places' => 200, 'flight_date' => $futureDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($futureDate . ' +1.5 hours'))],
            ['airplane_id' => 9, 'journey_id' => 9, 'state' => 1, 'price' => 190, 'remaining_places' => 400, 'flight_date' => $futureDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($futureDate . ' +2 hours'))],
            ['airplane_id' => 10, 'journey_id' => 10, 'state' => 1, 'price' => 210, 'remaining_places' => 80, 'flight_date' => $futureDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($futureDate . ' +2.5 hours'))],
            ['airplane_id' => 1, 'journey_id' => 11, 'state' => 0, 'price' => 130, 'remaining_places' => 0, 'flight_date' => $futureDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($futureDate . ' +2 hours'))],
            ['airplane_id' => 2, 'journey_id' => 12, 'state' => 0, 'price' => 250, 'remaining_places' => 0, 'flight_date' => $futureDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($futureDate . ' +3 hours'))],
            ['airplane_id' => 3, 'journey_id' => 13, 'state' => 0, 'price' => 180, 'remaining_places' => 0, 'flight_date' => $futureDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($futureDate . ' +2.5 hours'))],
            ['airplane_id' => 4, 'journey_id' => 14, 'state' => 0, 'price' => 170, 'remaining_places' => 350, 'flight_date' => $pastDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($pastDate . ' +2 hours'))],
            ['airplane_id' => 5, 'journey_id' => 15, 'state' => 0, 'price' => 190, 'remaining_places' => 400, 'flight_date' => $pastDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($pastDate . ' +3 hours'))],
            ['airplane_id' => 6, 'journey_id' => 16, 'state' => 0, 'price' => 200, 'remaining_places' => 300, 'flight_date' => $pastDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($pastDate . ' +2 hours'))],
            ['airplane_id' => 7, 'journey_id' => 17, 'state' => 0, 'price' => 210, 'remaining_places' => 250, 'flight_date' => $pastDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($pastDate . ' +2.5 hours'))],
            ['airplane_id' => 8, 'journey_id' => 18, 'state' => 0, 'price' => 230, 'remaining_places' => 200, 'flight_date' => $pastDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($pastDate . ' +3 hours'))],
            ['airplane_id' => 9, 'journey_id' => 19, 'state' => 0, 'price' => 170, 'remaining_places' => 0, 'flight_date' => $futureDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($futureDate . ' +1.5 hours'))],
            ['airplane_id' => 10, 'journey_id' => 20, 'state' => 0, 'price' => 250, 'remaining_places' => 0, 'flight_date' => $futureDate, 'arrival_date' => date('Y-m-d H:i:s', strtotime($futureDate . ' +2 hours'))]
        ];

        foreach ($flights as $flight) {
            Flight::create($flight);
        }
    }
}
