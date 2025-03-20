<?php

namespace Database\Seeders;

use App\Models\Destination;
use Illuminate\Database\Seeder;

class DestinationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $destinations = [
            ['name' => 'New York'],
            ['name' => 'London'],
            ['name' => 'Paris'],
            ['name' => 'Tokyo'],
            ['name' => 'Dubai'],
            ['name' => 'Sydney'],
            ['name' => 'Toronto'],
            ['name' => 'Madrid'],
            ['name' => 'Berlin'],
            ['name' => 'Rome']
        ];

        foreach ($destinations as $destination) {
            Destination::create($destination);
        }
    }
}
