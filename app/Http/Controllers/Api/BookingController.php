<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

        $hasReservation = $this->userHasReservation($user, $flight);

        if ($hasReservation) {
            return $this->responseWithError('The user already has a reservation on that flight', 409);
        }

        $this->makeBooking($user, $flight);

        return $this->responseWithSuccess([
            'message' => 'The flight has been booked successfully'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    private function getUserFromRequest(Request $request)
    {
        return $request->user();
    }

    private function getBookingsFromUser(User $user)
    {
        return $user->flights;
    }

    private function validateData(Request $request)
    {
        $rules = [
            'flight_id' => 'required|integer|min:0'
        ];

        return $request->validate($rules);
    }

    private function getFlightById(int $id)
    {
        return Flight::find($id);
    }

    private function flightIsAvaliable(Flight $flight)
    {
        return $flight->isAvailable();
    }

    private function userHasReservation(User $user, Flight $flight)
    {
        return $user->flights()->find($flight->id);
    }

    private function makeBooking(User $user, Flight $flight)
    {
        $user->flights()->save($flight);
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
