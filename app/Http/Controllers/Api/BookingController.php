<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/booking",
     *     tags={"Booking"},
     *     security={{ "pat": {} }},
     *     summary="List all Bookings in the system from the authenticated user",
     *     description="This endpoint returns a list of all bookings available in the system from the authenticated user.",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", description="Unique identifier of the booking", example=2),
     *             @OA\Property(property="airplane_id", type="integer", description="The ID of the airplane assigned to the flight", example=2),
     *             @OA\Property(property="journey_id", type="integer", description="The ID of the journey associated with the flight", example=2),
     *             @OA\Property(property="state", type="integer", description="Indicates the state of the booking (1 = active, 0 = inactive)", example=1),
     *             @OA\Property(property="remaining_places", type="integer", description="Number of available seats remaining for the flight", example=179),
     *             @OA\Property(property="flight_date", type="string", format="date-time", description="The scheduled date and time of the flight", example="2025-03-05 11:41:47"),
     *             @OA\Property(property="price", type="integer", description="The price of the flight ticket", example=200),
     *             @OA\Property(property="created_at", type="string", format="date-time", description="The timestamp when the booking was created", example="2025-03-05T10:41:47.000000Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", description="The timestamp when the booking was last updated", example="2025-03-06T18:29:03.000000Z"),
     *             @OA\Property(
     *                 property="pivot",
     *                 type="object",
     *                 description="Pivot table data for the relationship between user and flight",
     *                 @OA\Property(property="user_id", type="integer", description="The ID of the user who made the booking", example=2),
     *                 @OA\Property(property="flight_id", type="integer", description="The ID of the flight associated with the booking", example=2),
     *                 @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the pivot record was created", example="2025-03-05T10:41:49.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the pivot record was last updated", example="2025-03-05T10:41:49.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message when user is not authenticated", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message when user does not have sufficient permissions", example="Invalid scope(s) provided.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Too many requests",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Error message indicating the user has made too many requests", example="Too Many Attempts.")
     *         )
     *     )
     * )
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
