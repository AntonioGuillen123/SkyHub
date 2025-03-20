<?php

namespace Tests\Feature;

use App\Models\Flight;
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
            ->assertRedirect()
            ->assertSessionHas('message', $resultMessage)
            ->assertSessionHas('messageType', $resultMessageType);
    }

    public function test_CheckIfPostAnEntryOfBookingInWebWithWrongFlightId()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate(2);

        $data = [
            'flight_id' => 9999
        ];

        $response = $this->post(route('makeBooking'), $data);

        $resultMessage = 'The flight id does not exist';
        $resultMessageType = 'danger';

        $response
            ->assertRedirect()
            ->assertSessionHas('message', $resultMessage)
            ->assertSessionHas('messageType', $resultMessageType);
    }

    public function test_CheckIfPostAnEntryOfBookingInWebWithUnavailableFlight()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate(2);

        $flight = Flight::find(1);

        $flight->update(['state' => 0]);

        $flight->save();

        $data = [
            'flight_id' => 1
        ];

        $response = $this->post(route('makeBooking'), $data);

        $resultMessage = 'The flight is not available';
        $resultMessageType = 'danger';

        $response
            ->assertRedirect()
            ->assertSessionHas('message', $resultMessage)
            ->assertSessionHas('messageType', $resultMessageType);
    }

    public function test_CheckIfPostAnEntryOfBookingInWebWithUserAlreadyHasBooking()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate(2);

        $user = User::find(2);

        $flight = Flight::find(1);

        $user->flights()->save($flight);

        $data = [
            'flight_id' => 1
        ];

        $response = $this->post(route('makeBooking'), $data);

        $resultMessage = 'The user already has a reservation on that flight';
        $resultMessageType = 'danger';

        $response
            ->assertRedirect()
            ->assertSessionHas('message', $resultMessage)
            ->assertSessionHas('messageType', $resultMessageType);
    }

    public function test_CheckIfDeleteAnEntryOfBookingInWeb()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate(2);

        $user = User::find(2);

        $flight = Flight::find(1);

        $user->flights()->save($flight);

        $data = [
            'flight_id' => 1
        ];

        $response = $this->delete(route('cancelBooking'), $data);

        $resultMessage = 'The reservation has been cancelled successfully';
        $resultMessageType = 'success';

        $response
            ->assertRedirect()
            ->assertSessionHas('message', $resultMessage)
            ->assertSessionHas('messageType', $resultMessageType);
    }

    public function test_CheckIfDeleteAnEntryOfBookingInWebWithDoesNotHasBooking()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate(2);

        $data = [
            'flight_id' => 1
        ];

        $response = $this->delete(route('cancelBooking'), $data);

        $resultMessage = 'The user does not have any reservations on a plane with that id';
        $resultMessageType = 'danger';

        $response
            ->assertRedirect()
            ->assertSessionHas('message', $resultMessage)
            ->assertSessionHas('messageType', $resultMessageType);
    }
}
