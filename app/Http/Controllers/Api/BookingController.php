<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = $this->getUserFromRequest($request);

        $bookings = $this->getBookingsFromUser($user);

        return $this->responseWithSuccess($bookings);
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        $flight = $this->getFlightById($validated['flight_id']);

        if (!$flight) {
            return $this->responseWithError('The flight id does not exist', 404);
        }

        $isAvailable = $this->flightIsAvaliable($flight);

        if (!$isAvailable) {
            return $this->responseWithError('The flight is not available', 409);
        }

        $user = $this->getUserFromRequest($request);

        $hasReservation = $this->getBookingFromUserById($user, $flight->id);

        if ($hasReservation) {
            return $this->responseWithError('The user already has a reservation on that flight', 409);
        }

        $this->manageMakeBooking($user, $flight);

        return $this->responseWithSuccess([
            'message' => 'The flight has been booked successfully'
        ], 201);
    }

    public function show(string $id)
    {
        $flight = $this->getFlightById($id);

        if (!$flight) {
            return $this->responseWithError('The flight id does not exist', 404);
        }

        $users = $this->getUsersFromFlight($flight);

        return $this->responseWithSuccess($users, 200);
    }
    
    public function destroy(Request $request, string $id)
    {
        $user = $this->getUserFromRequest($request);

        $reservation = $this->getBookingFromUserById($user, $id);

        if (!$reservation) {
            return $this->responseWithError('The user does not have any reservations on a plane with that id', 404);
        }

        $this->manageCancelBooking($user, $reservation);

        return $this->responseWithSuccess([
            'message' => 'The reservation has been cancelled successfully'
        ], 200);
    }

    private function getUserFromRequest(Request $request)
    {
        return $request->user();
    }

    private function getUsersFromFlight(Flight $flight)
    {
        return $flight->users;
    }

    private function getBookingsFromUser(User $user)
    {
        return $user->flights;
    }

    private function getBookingFromUserById(User $user, string $id)
    {
        return $user->flights()->find($id);
    }

    private function getFlightById(int $id)
    {
        return Flight::find($id);
    }

    private function validateData(Request $request)
    {
        $rules = [
            'flight_id' => 'required|integer|min:0'
        ];

        return $request->validate($rules);
    }

    private function flightIsAvaliable(Flight $flight)
    {
        return $flight->isAvailable();
    }

    private function manageMakeBooking(User $user, Flight $flight)
    {
        $this->makeBooking($user, $flight);

        $this->decreasePlaceFromFlight($flight);
    }

    private function manageCancelBooking(User $user, Flight $flight)
    {
        $this->cancelBooking($user, $flight->id);

        $this->increasePlaceFromFlight($flight);
    }

    private function makeBooking(User $user, Flight $flight)
    {
        $user->flights()->save($flight);
    }

    private function cancelBooking(User $user, string $id)
    {
        $user->flights()->detach($id);
    }

    private function decreasePlaceFromFlight(Flight $flight)
    {
        $flight->remaining_places--;

        $flight->save();
    }

    private function increasePlaceFromFlight(Flight $flight)
    {
        $flight->remaining_places++;

        $flight->save();
    }

    private function responseWithSuccess(mixed $data, int $status = 200)
    {
        return response()->json($data, $status);
    }

    private function responseWithError(string $message, int $status)
    {
        return response()->json([
            'message' => $message . ' :('
        ], $status);
    }
}
