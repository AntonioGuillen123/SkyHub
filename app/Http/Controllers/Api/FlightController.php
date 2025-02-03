<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
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
    public function update(Request $request, Flight $flight)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Flight $flight)
    {
        //
    }

    private function getAllFlights(){
        return Flight::all();
    }

    private function getFlightById(int $id){
        return Flight::with(['airplane', 'journey'])->find($id);
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

    private function responseWithSuccess($data, $status = 200)
    {
        return response()->json($data, $status);
    }

    private function responseWithError($message, $status)
    {
        return response()->json([
            'message' => $message . ' :('
        ], $status);
    }
}
