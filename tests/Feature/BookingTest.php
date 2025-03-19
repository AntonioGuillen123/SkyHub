<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate(int $idUser)
    {
        $user = User::find($idUser);

        $this->actingAs($user);
    }

    public function test_CheckIfPostAnEntryOfBookingInWeb()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate(2);

        $data = [
            'flight_id' => 1
        ];

        $response = $this->post(route('makeBooking'), $data);

        $resultMessage = 'The flight has been booked successfully';
        $resultMessageType = 'success';

        $response
        ->assertRedirect(route('indexFlight'))
        ->assertSessionHas('message', $resultMessage)
        ->assertSessionHas('messageType', $resultMessageType);
    }
}
