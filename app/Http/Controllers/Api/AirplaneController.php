<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Airplane;
use Illuminate\Http\Request;

class AirplaneController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/airplane",
     *     tags={"Airplane"},
     *     summary="List all Airplanes in the system",
     *     description="This endpoint returns a list of all airplanes available in the system.",
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *                  type="object",
     *                  @OA\Property(property="id", type="integer", description="The unique identifier for the airplane.", example=1),
     *                  @OA\Property(property="name", type="string", description="The model or name of the airplane.", example="Boeing 747"),   
     *                  @OA\Property(property="maximum_places", type="integer", description="The maximum seating capacity of the airplane.", example=420),     
     *                  @OA\Property(property="created_at", type="date-time", description="The timestamp when the airplane record was created.", example="2025-02-04T15:10:13.000000Z"),     
     *                  @OA\Property(property="updated_at", type="date-time", description="The timestamp when the airplane record was last updated.", example="2025-02-04T15:10:13.000000Z")
     *              )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $airplanes = $this->getAllAirplanes();

        return $this->responseWithSuccess($airplanes);
    }

    /**
     * @OA\Post(
     *     path="/api/airplane",
     *     tags={"Airplane"},
     *     summary="Create a new Airplane",
     *     description="This endpoint creates a new airplane in the system.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Airplane data to be created.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", description="The model or name of the airplane.", example="Boeing 747"),
     *             @OA\Property(property="maximum_places", type="integer", description="The maximum seating capacity of the airplane.", example=420)
     *         )    
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer", description="The unique identifier for the airplane.", example=1),
     *              @OA\Property(property="name", type="string", description="The model or name of the airplane.", example="Boeing 747"),   
     *              @OA\Property(property="maximum_places", type="integer", description="The maximum seating capacity of the airplane.", example=420),     
     *              @OA\Property(property="created_at", type="date-time", description="The timestamp when the airplane record was created.", example="2025-02-04T15:10:13.000000Z"),     
     *              @OA\Property(property="updated_at", type="date-time", description="The timestamp when the airplane record was last updated.", example="2025-02-04T15:10:13.000000Z")
     *
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The name field is required"),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="name", type="array",
     *                     @OA\Items(type="string", example="The name field is required.")
     *                 ),
     *                 @OA\Property(property="maximum_places", type="array", 
     *                     @OA\Items(type="string", example="The maximum places field is required.")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $this->validateData($request, 'store');

        $airplane = Airplane::create($validated)->refresh();

        return $this->responseWithSuccess($airplane, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/airplane/{id}",
     *     tags={"Airplane"},
     *     summary="Get an Airplane by Id",
     *     description="This endpoint returns the details of an airplane by Id.",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="The Id of the airplane to get",
     *          @OA\Schema(type="integer", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer", description="The unique identifier for the airplane.", example=1),
     *              @OA\Property(property="name", type="string", description="The model or name of the airplane.", example="Boeing 747"),   
     *              @OA\Property(property="maximum_places", type="integer", description="The maximum seating capacity of the airplane.", example=420),     
     *              @OA\Property(property="created_at", type="date-time", description="The timestamp when the airplane record was created.", example="2025-02-04T15:10:13.000000Z"),     
     *              @OA\Property(property="updated_at", type="date-time", description="The timestamp when the airplane record was last updated.", example="2025-02-04T15:10:13.000000Z")
     *
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The airplane id does not exist :(")
     *         )
     *     )
     * )
     */
    public function show(int $id)
    {
        $airplane = $this->getAirplaneById($id);

        if (!$airplane) {
            return $this->responseWithError('The airplane id does not exist', 404);
        }

        return $this->responseWithSuccess($airplane);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $airplane = $this->getAirplaneById($id);

        if (!$airplane) {
            return $this->responseWithError('The airplane id does not exist', 404);
        }

        $validated = $this->validateData($request, 'update');

        $airplane->update($validated);

        $airplane->refresh();

        return $this->responseWithSuccess($airplane);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $airplane = $this->getAirplaneById($id);

        if (!$airplane) {
            return $this->responseWithError('The airplane id does not exist', 404);
        }

        $airplane->delete();

        return $this->responseWithSuccess([], 204);
    }

    private function getAllAirplanes()
    {
        return Airplane::all();
    }

    private function getAirplaneById(int $id)
    {
        return Airplane::find($id);
    }

    private function validateData(Request $request, string $option)
    {
        $rules = $option === 'store'
            ? [
                'name' => 'required|string|max:255',
                'maximum_places' => 'required|integer|min:0'
            ]
            : [
                'name' => 'string|max:255',
                'maximum_places' => 'integer|min:0'
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
