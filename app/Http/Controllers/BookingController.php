<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        $flight = $this->getFlightById($validated['flight_id']);

        if (!$flight) {
            return $this->responseWithMessage('The flight id does not exist');
        }

        $isAvailable = $this->flightIsAvaliable($flight);

        if (!$isAvailable) {
            return $this->responseWithMessage('The flight is not available');
        }

        $user = $this->getUserFromRequest($request);

        $hasReservation = $this->getBookingFromUserById($user, $flight->id);

        if ($hasReservation) {
            return $this->responseWithMessage('The user already has a reservation on that flight');
        }

        $this->manageMakeBooking($user, $flight);

        return $this->responseWithMessage('The flight has been booked successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
    public function destroy(Request $request)
    {
        $validated = $this->validateData($request);

        $user = $this->getUserFromRequest($request);

        $reservation = $this->getBookingFromUserById($user, $validated['flight_id']);

        if (!$reservation) {
            return $this->responseWithMessage('The user does not have any reservations on a plane with that id');
        }

        $this->manageCancelBooking($user, $reservation);

        return $this->responseWithMessage('The reservation has been cancelled successfully');
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

    private function getUserFromRequest(Request $request)
    {
        return $request->user();
    }

    private function getBookingFromUserById(User $user, string $id)
    {
        return $user->flights()->find($id);
    }

    private function manageMakeBooking(User $user, Flight $flight)
    {
        $this->makeBooking($user, $flight);

        $this->decreasePlaceFromFlight($flight);
    }

    private function makeBooking(User $user, Flight $flight)
    {
        $user->flights()->save($flight);
    }

    private function decreasePlaceFromFlight(Flight $flight)
    {
        $flight->remaining_places--;

        $flight->save();
    }

    private function manageCancelBooking(User $user, Flight $flight)
    {
        $this->cancelBooking($user, $flight->id);

        $this->increasePlaceFromFlight($flight);
    }

    private function cancelBooking(User $user, string $id)
    {
        $user->flights()->detach($id);
    }

    private function increasePlaceFromFlight(Flight $flight)
    {
        $flight->remaining_places++;

        $flight->save();
    }

    private function responseWithMessage(mixed $message)
    {
        return redirect()->route('indexFlight')->with('message', $message);
    }
}
