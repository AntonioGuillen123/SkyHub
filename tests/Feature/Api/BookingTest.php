<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate($idUser, $scopes){
        $user = User::find($idUser);

        Passport::actingAs(
            $user,
            $scopes
        );
    }

    public function test_CheckIfRecieveAllEntriesOfBookingsFromUserInJsonFile(){
        $this->seed(DatabaseSeeder::class);

        $this->authenticate(2, ['list-bookings']);

        $response = $this->getJson(route('apiIndexBooking'));
        
        $response
            ->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_CheckIfPostAnEntryOfBookingInJsonFile(){
        $this->seed(DatabaseSeeder::class);

        $this->authenticate(2, ['make-booking']);

        $data = [
            'flight_id' => 1
        ];

        $response = $this->postJson(route('apiMakeBooking'), $data);

        $resultData = [
            'message' => 'The flight has been booked successfully'
        ];
        
        $response
            ->assertStatus(201)
            ->assertJsonFragment($resultData);
    }
}
