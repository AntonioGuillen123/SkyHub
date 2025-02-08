<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Airplane;
use App\Models\Flight;
use App\Models\Journey;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/flight",
     *     tags={"Flight"},
     *     summary="List all Flights in the system",
     *     description="This endpoint returns a list of all flights available in the system.",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", description="The unique identifier for the flight.", example=1),
     *                  @OA\Property(property="airplane_id", type="integer", description="The unique identifier of the airplane associated with the flight. This field references the Airplane model.", example=1),   
     *                  @OA\Property(property="journey_id", type="integer", description="The unique identifier of the journey associated with the flight. This field references the Journey model.", example=1),     
     *                  @OA\Property(property="state", type="integer", description="The state of the flight.", example=1),     
     *                  @OA\Property(property="remaining_places", type="integer", description="The remaining places of the flight.", example=420),     
     *                  @OA\Property(property="created_at", type="date-time", description="The timestamp when the flight record was created.", example="2025-02-04T15:10:13.000000Z"),     
     *                  @OA\Property(property="updated_at", type="date-time", description="The timestamp when the flight record was last updated.", example="2025-02-04T15:10:13.000000Z")
     *              )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $flights = $this->getAllFlights();

        return $this->responseWithSuccess($flights);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateData($request, 'store');

        $airplane = $this->getAirplaneById($validated['airplane_id']);

        if (!$airplane) {
            return $this->responseWithError('The airplane id does not exist', 404);
        }

        $journey = $this->getAirplaneById($validated['journey_id']);

        if (!$journey) {
            return $this->responseWithError('The journey id does not exist', 404);
        }

        $flight = Flight::create($validated)->refresh();

        return $this->responseWithSuccess($flight, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $flight = $this->getFlightById($id);

        if (!$flight) {
            return $this->responseWithError('The flight id does not exist', 404);
        }

        return $this->responseWithSuccess($flight);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $flight = $this->getFlightById($id);

        if (!$flight) {
            return $this->responseWithError('The flight id does not exist', 404);
        }

        $validated = $this->validateData($request, 'update');

        $airplane = $this->getAirplaneById($validated['airplane_id'] ?? $flight->airplane_id);

        if (!$airplane) {
            return $this->responseWithError('The airplane id does not exist', 404);
        }

        $journey = $this->getJourneyById($validated['journey_id'] ?? $flight->journey_id);

        if (!$journey) {
            return $this->responseWithError('The journey id does not exist', 404);
        }

        $flight->update($validated);

        $flight->refresh();

        return $this->responseWithSuccess($flight);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $flight = $this->getFlightById($id);

        if (!$flight) {
            return $this->responseWithError('The flight id does not exist', 404);
        }

        $flight->delete();

        return $this->responseWithSuccess([], 204);
    }

    private function getAllFlights()
    {
        return Flight::all();
    }

    private function getFlightById(int $id)
    {
        return Flight::with(['airplane', 'journey'])->find($id);
    }

    private function getAirplaneById(int $id)
    {
        return Airplane::find($id);
    }

    private function getJourneyById(int $id)
    {
        return Journey::find($id);
    }

    private function validateData(Request $request, string $option)
    {
        $rules = $option === 'store'
            ? [
                'airplane_id' => 'required|integer|min:0',
                'journey_id' => 'required|integer|min:0',
                'state' => 'boolean',
                'remaining_places' => 'integer|min:0',
                'flight_date' => 'date_format:Y-m-d H:i'
            ]
            : [
                'airplane_id' => 'integer|min:0',
                'journey_id' => 'integer|min:0',
                'state' => 'boolean',
                'remaining_places' => 'integer|min:0',
                'flight_date' => 'date_format:m-d-Y H:i'
            ];

        return $request->validate($rules);
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
