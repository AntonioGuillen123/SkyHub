<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookings = [
            [
                'user_id' => 1, 
                'flight_id' => 1,
            ],
            [
                'user_id' => 2, 
                'flight_id' => 2,
            ],
            [
                'user_id' => 3, 
                'flight_id' => 3,
            ],
            [
                'user_id' => 4, 
                'flight_id' => 4,
            ],
            [
                'user_id' => 5, 
                'flight_id' => 5,
            ],
            [
                'user_id' => 6, 
                'flight_id' => 6,
            ],
            [
                'user_id' => 7, 
                'flight_id' => 7,
            ],
            [
                'user_id' => 8, 
                'flight_id' => 8,
            ],
            [
                'user_id' => 9, 
                'flight_id' => 9,
            ],
            [
                'user_id' => 10, 
                'flight_id' => 10,
            ],
        ];
        
        foreach ($bookings as $booking) {
            $booking['created_at'] = now();
            $booking['updated_at'] = now();

            DB::table('booking')->insert($booking);
        }
    }
}
