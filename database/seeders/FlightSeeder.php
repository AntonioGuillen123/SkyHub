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
        $flightDate = date('Y-m-d H:i:s', strtotime('+1 year'));
        $arrivalDate = date('Y-m-d H:i:s', strtotime('+1 year +1 day'));

        $flights = [
            ['airplane_id' => 1, 'journey_id' => 1, 'state' => 1, 'price' => 100, 'flight_date' => $flightDate, 'arrival_date' => $arrivalDate],
            ['airplane_id' => 2, 'journey_id' => 2, 'state' => 1, 'price' => 200, 'flight_date' => $flightDate, 'arrival_date' => $arrivalDate],
            ['airplane_id' => 3, 'journey_id' => 3, 'state' => 0, 'price' => 150, 'flight_date' => $flightDate, 'arrival_date' => $arrivalDate],
            ['airplane_id' => 1, 'journey_id' => 4, 'state' => 1, 'price' => 120, 'flight_date' => $flightDate, 'arrival_date' => $arrivalDate],
            ['airplane_id' => 2, 'journey_id' => 5, 'state' => 1, 'price' => 220, 'flight_date' => $flightDate, 'arrival_date' => $arrivalDate],
            ['airplane_id' => 3, 'journey_id' => 6, 'state' => 0, 'price' => 170, 'flight_date' => $flightDate, 'arrival_date' => $arrivalDate],
            ['airplane_id' => 1, 'journey_id' => 7, 'state' => 1, 'price' => 130, 'flight_date' => $flightDate, 'arrival_date' => $arrivalDate],
            ['airplane_id' => 2, 'journey_id' => 8, 'state' => 1, 'price' => 180, 'flight_date' => $flightDate, 'arrival_date' => $arrivalDate],
            ['airplane_id' => 3, 'journey_id' => 9, 'state' => 0, 'price' => 160, 'flight_date' => $flightDate, 'arrival_date' => $arrivalDate],
            ['airplane_id' => 1, 'journey_id' => 10, 'state' => 1, 'price' => 140, 'flight_date' => $flightDate, 'arrival_date' => $arrivalDate],
        ];

        foreach ($flights as $flight) {
            Flight::create($flight);
        }
    }
}
