<?php

namespace Tests\Feature\Api;

use App\Models\Flight;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    private function authenticate($idUser, $scopes)
    {
        $user = User::find($idUser);

        Passport::actingAs(
            $user,
            $scopes
        );
    }

    public function test_CheckIfRecieveAllEntriesOfBookingsFromUserInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate(2, ['list-bookings']);

        $response = $this->getJson(route('apiIndexBooking'));

        $response
            ->assertStatus(200)
            ->assertJsonCount(5);
    }

    public function test_CheckIfPostAnEntryOfBookingInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate(2, ['make-booking']);

        $data = [
            'flight_id' => 2
        ];

        $response = $this->postJson(route('apiMakeBooking'), $data);

        $resultData = [
            'message' => 'The flight has been booked successfully'
        ];

        $response
            ->assertStatus(201)
            ->assertJsonFragment($resultData);
    }

    public function test_CheckIfPostAnEntryOfBookingWithWrongFlightIdInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate(2, ['make-booking']);

        $data = [
            'flight_id' => 9999
        ];

        $response = $this->postJson(route('apiMakeBooking'), $data);

        $resultData = [
            'message' => 'The flight id does not exist :('
        ];

        $response
            ->assertStatus(404)
            ->assertJsonFragment($resultData);
    }

    public function test_CheckIfPostAnEntryOfBookingWithUnavailableFlightIdInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate(2, ['make-booking']);

        $data = [
            'flight_id' => 11
        ];

        $response = $this->postJson(route('apiMakeBooking'), $data);

        $resultData = [
            'message' => 'The flight is not available :('
        ];

        $response
            ->assertStatus(409)
            ->assertJsonFragment($resultData);
    }

    public function test_CheckIfPostAnEntryOfBookingWithAlreadyReservationInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate(2, ['make-booking']);

        $data = [
            'flight_id' => 1
        ];

        $this->postJson(route('apiMakeBooking'), $data);

        $response = $this->postJson(route('apiMakeBooking'), $data);

        $resultData = [
            'message' => 'The user already has a reservation on that flight :('
        ];

        $response
            ->assertStatus(409)
            ->assertJsonFragment($resultData);
    }

    public function test_CheckIfRecieveAllUsersByFlightIdInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate(1, ['list-all-bookings']);

        $response = $this->getJson(route('apiShowBooking', 1));

        $response
            ->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_CheckIfRecieveAllUsersByWrongFlightIdInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate(1, ['list-all-bookings']);

        $response = $this->getJson(route('apiShowBooking', 9999));

        $resultData = [
            'message' => 'The flight id does not exist :('
        ];

        $response
            ->assertStatus(404)
            ->assertJsonFragment($resultData);
    }

    public function test_CheckIfDeleteAnEntryOfBookingByFlightIdInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate(2, ['cancel-booking']);

        $response = $this->deleteJson(route('apiCancelBooking', 1));

        $resultData = [
            'message' => 'The reservation has been cancelled successfully'
        ];

        $response
            ->assertStatus(200)
            ->assertJsonFragment($resultData);
    }

    public function test_CheckIfDeleteAnEntryOfBookingByWrongFlightIdInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate(2, ['cancel-booking']);

        $response = $this->deleteJson(route('apiCancelBooking', 2));

        $resultData = [
            'message' => 'The user does not have any reservations on a plane with that id :('
        ];

        $response
            ->assertStatus(404)
            ->assertJsonFragment($resultData);
    }

    public function test_CheckIfDeleteAnEntryOfBookingByDateFlightPassedInJsonFile()
    {
        $this->seed(DatabaseSeeder::class);

        $this->authenticate(2, ['cancel-booking']);

        $flight = Flight::find(2);

        $flight->flight_date = now()->subYear(1)->format('Y-m-d H:i');

        $flight->save();

        $response = $this->deleteJson(route('apiCancelBooking', 2));

        $resultData = [
            'message' => 'The reservation cannot be cancelled because the flight date has passed :('
        ];

        $response
            ->assertStatus(409)
            ->assertJsonFragment($resultData);
    }
}
