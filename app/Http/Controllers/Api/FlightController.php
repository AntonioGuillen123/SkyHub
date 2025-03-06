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
     *                  @OA\Property(property="state", type="boolean", description="The state of the flight.", example=true),
     *                  @OA\Property(property="flight_date", type="string", format="date-time", description="The scheduled date and time of the flight.", example="2025-12-31 15:40"),     
     *                  @OA\Property(property="remaining_places", type="integer", description="The remaining places of the flight.", example=420),     
     *                  @OA\Property(property="price", type="decimal", description="The price of the flight.", example=555),     
     *                  @OA\Property(property="created_at", type="date-time", description="The timestamp when the flight record was created.", example="2025-02-04T15:10:13.000000Z"),     
     *                  @OA\Property(property="updated_at", type="date-time", description="The timestamp when the flight record was last updated.", example="2025-02-04T15:10:13.000000Z")
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Too many attempts",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Too Many Attempts.")
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
     * @OA\Post(
     *     path="/api/flight",
     *     tags={"Flight"},
     *     summary="Create a new flight",
     *     description="This endpoint allows creating a new flight in the system.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              type="object",
     *              required={"airplane_id", "journey_id", "price"},
     *              @OA\Property(property="airplane_id", type="integer", description="The unique identifier of the airplane associated with the flight.", example=1),
     *              @OA\Property(property="journey_id", type="integer", description="The unique identifier of the journey associated with the flight.", example=1),
     *              @OA\Property(property="state", type="boolean", description="The state of the flight.", example=true),
     *              @OA\Property(property="remaining_places", type="integer", description="The remaining places of the flight.", example=420),
     *              @OA\Property(property="price", type="decimal", description="The price of the flight.", example=555),     
     *              @OA\Property(property="flight_date", type="string", format="date-time", description="The scheduled date and time of the flight.", example="2025-12-31 15:40")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer", description="The unique identifier for the flight.", example=1),
     *              @OA\Property(property="airplane_id", type="integer", description="The unique identifier of the airplane associated with the flight.", example=1),
     *              @OA\Property(property="journey_id", type="integer", description="The unique identifier of the journey associated with the flight.", example=1),
     *              @OA\Property(property="state", type="integer", description="The state of the flight.", example=1),
     *              @OA\Property(property="remaining_places", type="integer", description="The remaining available seats on the flight.", example=420),
     *              @OA\Property(property="flight_date", type="string", format="date-time", description="The scheduled date and time of the flight.", example="2025-12-31 15:40"),
     *              @OA\Property(property="price", type="decimal", description="The price of the flight.", example=555),     
     *              @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the flight record was created.", example="2025-02-04T15:10:13.000000Z"),
     *              @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the flight record was last updated.", example="2025-02-04T15:10:13.000000Z"),
     *              
     *              @OA\Property(property="airplane", type="object", description="Details of the airplane associated with the flight.",
     *                  @OA\Property(property="id", type="integer", description="The unique identifier of the airplane.", example=1),
     *                  @OA\Property(property="name", type="string", description="The model or name of the airplane.", example="Boeing 747"),
     *                  @OA\Property(property="maximum_places", type="integer", description="The maximum number of seats available in the airplane.", example=420),
     *                  @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the airplane record was created.", example="2025-02-04T15:10:13.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the airplane record was last updated.", example="2025-02-04T15:10:13.000000Z")
     *              ),
     *              
     *              @OA\Property(property="journey", type="object", description="Details of the journey associated with the flight.",
     *                  @OA\Property(property="id", type="integer", description="The unique identifier of the journey.", example=1),
     *                  @OA\Property(property="departure_id", type="integer", description="The ID of the departure destination.", example=1),
     *                  @OA\Property(property="arrival_id", type="integer", description="The ID of the arrival destination.", example=2),
     *                  @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the journey record was created.", example="2025-02-04T15:10:13.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the journey record was last updated.", example="2025-02-04T15:10:13.000000Z"),
     *                  
     *                  @OA\Property(property="destination_departure", type="object", description="Details of the departure destination.",
     *                      @OA\Property(property="id", type="integer", description="The unique identifier of the departure destination.", example=1),
     *                      @OA\Property(property="name", type="string", description="The name of the departure destination.", example="New York"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the destination record was created.", example="2025-02-04T15:10:13.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the destination record was last updated.", example="2025-02-04T15:10:13.000000Z")
     *                  ),
     *                  
     *                  @OA\Property(property="destination_arrival", type="object", description="Details of the arrival destination.",
     *                      @OA\Property(property="id", type="integer", description="The unique identifier of the arrival destination.", example=2),
     *                      @OA\Property(property="name", type="string", description="The name of the arrival destination.", example="London"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the destination record was created.", example="2025-02-04T15:10:13.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the destination record was last updated.", example="2025-02-04T15:10:13.000000Z")
     *                  )
     *              )
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
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="airplane_id", type="array",
     *                     @OA\Items(type="string", example="The airplane_id field is required.")
     *                 ),
     *                 @OA\Property(property="journey_id", type="array",
     *                     @OA\Items(type="string", example="The journey_id field is required.")
     *                 ),
     *                 @OA\Property(property="state", type="array",
     *                     @OA\Items(type="string", example="The state field must be true or false.")
     *                 ),
     *                 @OA\Property(property="remaining_places", type="array",
     *                     @OA\Items(type="string", example="The remaining_places field must be at least 0.")
     *                 ),
     *                 @OA\Property(property="flight_date", type="array",
     *                     @OA\Items(type="string", example="The flight_date field does not match the format Y-m-d H:i.")
     *                 ),
     *                 @OA\Property(property="price", type="array",
     *                     @OA\Items(type="string", example="The price field must be at least 0.")
     *                 )
     *             )     
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Too many attempts",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Too Many Attempts.")
     *         )
     *     )
     * )
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

        $flight = $this->createFlightWithRelationShips($validated);

        return $this->responseWithSuccess($flight, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/flight/{id}",
     *     tags={"Flight"},
     *     summary="Get a Flight by Id",
     *     description="This endpoint returns the details of a flight by Id.",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="The Id of the flight to get",
     *          @OA\Schema(type="integer", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer", description="The unique identifier for the flight.", example=1),
     *              @OA\Property(property="airplane_id", type="integer", description="The unique identifier of the airplane associated with the flight.", example=1),
     *              @OA\Property(property="journey_id", type="integer", description="The unique identifier of the journey associated with the flight.", example=1),
     *              @OA\Property(property="state", type="integer", description="The state of the flight.", example=1),
     *              @OA\Property(property="remaining_places", type="integer", description="The remaining available seats on the flight.", example=420),
     *              @OA\Property(property="flight_date", type="string", format="date-time", description="The scheduled date and time of the flight.", example="2025-12-31 15:40"),
     *              @OA\Property(property="price", type="decimal", description="The price of the flight.", example=555),
     *              @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the flight record was created.", example="2025-02-04T15:10:13.000000Z"),
     *              @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the flight record was last updated.", example="2025-02-04T15:10:13.000000Z"),
     *              
     *              @OA\Property(property="airplane", type="object", description="Details of the airplane associated with the flight.",
     *                  @OA\Property(property="id", type="integer", description="The unique identifier of the airplane.", example=1),
     *                  @OA\Property(property="name", type="string", description="The model or name of the airplane.", example="Boeing 747"),
     *                  @OA\Property(property="maximum_places", type="integer", description="The maximum number of seats available in the airplane.", example=420),
     *                  @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the airplane record was created.", example="2025-02-04T15:10:13.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the airplane record was last updated.", example="2025-02-04T15:10:13.000000Z")
     *              ),
     *              
     *              @OA\Property(property="journey", type="object", description="Details of the journey associated with the flight.",
     *                  @OA\Property(property="id", type="integer", description="The unique identifier of the journey.", example=1),
     *                  @OA\Property(property="departure_id", type="integer", description="The ID of the departure destination.", example=1),
     *                  @OA\Property(property="arrival_id", type="integer", description="The ID of the arrival destination.", example=2),
     *                  @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the journey record was created.", example="2025-02-04T15:10:13.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the journey record was last updated.", example="2025-02-04T15:10:13.000000Z"),
     *                  
     *                  @OA\Property(property="destination_departure", type="object", description="Details of the departure destination.",
     *                      @OA\Property(property="id", type="integer", description="The unique identifier of the departure destination.", example=1),
     *                      @OA\Property(property="name", type="string", description="The name of the departure destination.", example="New York"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the destination record was created.", example="2025-02-04T15:10:13.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the destination record was last updated.", example="2025-02-04T15:10:13.000000Z")
     *                  ),
     *                  
     *                  @OA\Property(property="destination_arrival", type="object", description="Details of the arrival destination.",
     *                      @OA\Property(property="id", type="integer", description="The unique identifier of the arrival destination.", example=2),
     *                      @OA\Property(property="name", type="string", description="The name of the arrival destination.", example="London"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the destination record was created.", example="2025-02-04T15:10:13.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the destination record was last updated.", example="2025-02-04T15:10:13.000000Z")
     *                  )
     *              )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The flight id does not exist :(")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Too many attempts",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Too Many Attempts.")
     *         )
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/flight/{id}",
     *     tags={"Flight"},
     *     summary="Update a flight by Id",
     *     description="This endpoint allows updating an existing flight by id.",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The unique identifier of the flight to be updated.",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="airplane_id", type="integer", description="The unique identifier of the airplane associated with the flight.", example=1),
     *              @OA\Property(property="journey_id", type="integer", description="The unique identifier of the journey associated with the flight.", example=1),
     *              @OA\Property(property="state", type="boolean", description="The state of the flight.", example=true),
     *              @OA\Property(property="remaining_places", type="integer", description="The remaining seats of the flight.", example=420),
     *              @OA\Property(property="price", type="decimal", description="The price of the flight.", example=555),
     *              @OA\Property(property="flight_date", type="string", format="date-time", description="The scheduled date and time of the flight.", example="2025-12-31 15:40")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer", description="The unique identifier for the flight.", example=1),
     *              @OA\Property(property="airplane_id", type="integer", description="The unique identifier of the airplane associated with the flight.", example=1),
     *              @OA\Property(property="journey_id", type="integer", description="The unique identifier of the journey associated with the flight.", example=1),
     *              @OA\Property(property="state", type="integer", description="The state of the flight.", example=1),
     *              @OA\Property(property="remaining_places", type="integer", description="The remaining available seats on the flight.", example=420),
     *              @OA\Property(property="flight_date", type="string", format="date-time", description="The scheduled date and time of the flight.", example="2025-12-31 15:40"),
     *              @OA\Property(property="price", type="decimal", description="The price of the flight.", example=555),
     *              @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the flight record was created.", example="2025-02-04T15:10:13.000000Z"),
     *              @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the flight record was last updated.", example="2025-02-04T15:10:13.000000Z"),
     *              
     *              @OA\Property(property="airplane", type="object", description="Details of the airplane associated with the flight.",
     *                  @OA\Property(property="id", type="integer", description="The unique identifier of the airplane.", example=1),
     *                  @OA\Property(property="name", type="string", description="The model or name of the airplane.", example="Boeing 747"),
     *                  @OA\Property(property="maximum_places", type="integer", description="The maximum number of seats available in the airplane.", example=420),
     *                  @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the airplane record was created.", example="2025-02-04T15:10:13.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the airplane record was last updated.", example="2025-02-04T15:10:13.000000Z")
     *              ),
     *              
     *              @OA\Property(property="journey", type="object", description="Details of the journey associated with the flight.",
     *                  @OA\Property(property="id", type="integer", description="The unique identifier of the journey.", example=1),
     *                  @OA\Property(property="departure_id", type="integer", description="The ID of the departure destination.", example=1),
     *                  @OA\Property(property="arrival_id", type="integer", description="The ID of the arrival destination.", example=2),
     *                  @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the journey record was created.", example="2025-02-04T15:10:13.000000Z"),
     *                  @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the journey record was last updated.", example="2025-02-04T15:10:13.000000Z"),
     *                  
     *                  @OA\Property(property="destination_departure", type="object", description="Details of the departure destination.",
     *                      @OA\Property(property="id", type="integer", description="The unique identifier of the departure destination.", example=1),
     *                      @OA\Property(property="name", type="string", description="The name of the departure destination.", example="New York"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the destination record was created.", example="2025-02-04T15:10:13.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the destination record was last updated.", example="2025-02-04T15:10:13.000000Z")
     *                  ),
     *                  
     *                  @OA\Property(property="destination_arrival", type="object", description="Details of the arrival destination.",
     *                      @OA\Property(property="id", type="integer", description="The unique identifier of the arrival destination.", example=2),
     *                      @OA\Property(property="name", type="string", description="The name of the arrival destination.", example="London"),
     *                      @OA\Property(property="created_at", type="string", format="date-time", description="Timestamp when the destination record was created.", example="2025-02-04T15:10:13.000000Z"),
     *                      @OA\Property(property="updated_at", type="string", format="date-time", description="Timestamp when the destination record was last updated.", example="2025-02-04T15:10:13.000000Z")
     *                  )
     *              )
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
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", description="Flight not found or one of the related IDs does not exist.", example="The flight id does not exist :(")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="airplane_id", type="array",
     *                     @OA\Items(type="string", example="The airplane_id field must be an integer.")
     *                 ),
     *                 @OA\Property(property="journey_id", type="array",
     *                     @OA\Items(type="string", example="The journey_id field must be an integer.")
     *                 ),
     *                 @OA\Property(property="state", type="array",
     *                     @OA\Items(type="string", example="The state field must be true or false.")
     *                 ),
     *                 @OA\Property(property="remaining_places", type="array",
     *                     @OA\Items(type="string", example="The remaining_places field must be at least 0.")
     *                 ),
     *                 @OA\Property(property="flight_date", type="array",
     *                     @OA\Items(type="string", example="The flight_date field does not match the format Y-m-d H:i.")
     *                 ),
     *                 @OA\Property(property="price", type="array",
     *                     @OA\Items(type="string", example="The price field must be at least 0.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Too many attempts",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Too Many Attempts.")
     *         )
     *     )
     * )
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

        $flight = $this->updateFlightWithRelationShips($flight, $validated);

        return $this->responseWithSuccess($flight);
    }

    /**
     * @OA\Delete(
     *     path="/api/flight/{id}",
     *     tags={"Flight"},
     *     summary="Delete a Fligt by Id",
     *     description="This endpoint deletes a flight from the system by its unique Id.",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="The Id of the flight to delete",
     *          @OA\Schema(type="integer", example="1")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No Content",
     *         @OA\JsonContent(
     *             type="object",
     *             example={}
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
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The flight id does not exist :(")
     *         )
     *     ),
     *     @OA\Response(
     *         response=429,
     *         description="Too many attempts",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Too Many Attempts.")
     *         )
     *     )
     * )
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
        return Flight::with(['airplane', 'journey', 'journey.destinationDeparture', 'journey.destinationArrival'])->find($id);
    }

    private function getAirplaneById(int $id)
    {
        return Airplane::find($id);
    }

    private function getJourneyById(int $id)
    {
        return Journey::find($id);
    }

    private function createFlightWithRelationShips($data)
    {
        return Flight::create($data)->fresh(['journey', 'journey.destinationDeparture', 'journey.destinationArrival']);
    }

    private function updateFlightWithRelationShips($flight, $data)
    {
        $flight->update($data);

        return $flight->fresh(['journey', 'journey.destinationDeparture', 'journey.destinationArrival']);
    }

    private function validateData(Request $request, string $option)
    {
        $rules = $option === 'store'
            ? [
                'airplane_id' => 'required|integer|min:0',
                'journey_id' => 'required|integer|min:0',
                'state' => 'boolean',
                'remaining_places' => 'integer|min:0',
                'flight_date' => 'date_format:Y-m-d H:i',
                'price' => 'required|decimal|min:0'
            ]
            : [
                'airplane_id' => 'integer|min:0',
                'journey_id' => 'integer|min:0',
                'state' => 'boolean',
                'remaining_places' => 'integer|min:0',
                'flight_date' => 'date_format:Y-m-d H:i',
                'price' => 'decimal|min:0'
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
