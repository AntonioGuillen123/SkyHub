<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\AirplaneSeeder;
use Database\Seeders\DestinationSeeder;
use Database\Seeders\JourneySeeder;
use Database\Seeders\FlightSeeder;
use Database\Seeders\RoleUserSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\BookingSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AirplaneSeeder::class,
            DestinationSeeder::class,
            JourneySeeder::class,
            FlightSeeder::class,
            RoleUserSeeder::class,
            UserSeeder::class,
            BookingSeeder::class
        ]);
    }
}
