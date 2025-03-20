<?php

namespace Database\Seeders;

use App\Models\Airplane;
use Illuminate\Database\Seeder;

class AirplaneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $airplanes = [
            ['name' => 'Boeing 747', 'maximum_places' => 420],
            ['name' => 'Airbus A320', 'maximum_places' => 180],
            ['name' => 'Embraer E190', 'maximum_places' => 114],
            ['name' => 'Boeing 777', 'maximum_places' => 396],
            ['name' => 'Airbus A380', 'maximum_places' => 615],
            ['name' => 'Cessna 172', 'maximum_places' => 361],
            ['name' => 'Bombardier CRJ700', 'maximum_places' => 278],
            ['name' => 'Boeing 737', 'maximum_places' => 215],
            ['name' => 'Airbus A350', 'maximum_places' => 440],
            ['name' => 'Antonov An-225', 'maximum_places' => 88]
        ];

        foreach ($airplanes as $airplane) {
            Airplane::create($airplane);
        }
    }
}
