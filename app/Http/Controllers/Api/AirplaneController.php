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
     *     security={{ "pat": {} }},
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
     *                  @OA\Property(property="created_at", type="string", format="date-time", description="The timestamp when the airplane record was created.", example="2025-02-04T15:10:13.000000Z"),     
     *                  @OA\Property(property="updated_at", type="string", format="date-time", description="The timestamp when the airplane record was last updated.", example="2025-02-04T15:10:13.000000Z")
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
     *              required={"name", "maximum_places"},
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
     *              @OA\Property(property="created_at", type="string", format="date-time", description="The timestamp when the airplane record was created.", example="2025-02-04T15:10:13.000000Z"),     
     *              @OA\Property(property="updated_at", type="string", format="date-time", description="The timestamp when the airplane record was last updated.", example="2025-02-04T15:10:13.000000Z")
     *
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
     *                 @OA\Property(property="name", type="array",
     *                     @OA\Items(type="string", example="The name field is required.")
     *                 ),
     *                 @OA\Property(property="maximum_places", type="array", 
     *                     @OA\Items(type="string", example="The maximum places field is required.")
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
     *              @OA\Property(property="created_at", type="string", format="date-time", description="The timestamp when the airplane record was created.", example="2025-02-04T15:10:13.000000Z"),     
     *              @OA\Property(property="updated_at", type="string", format="date-time", description="The timestamp when the airplane record was last updated.", example="2025-02-04T15:10:13.000000Z")
     *
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
     *             @OA\Property(property="message", type="string", example="The airplane id does not exist :(")
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
        $airplane = $this->getAirplaneById($id);

        if (!$airplane) {
            return $this->responseWithError('The airplane id does not exist', 404);
        }

        return $this->responseWithSuccess($airplane);
    }

    /**
     * @OA\Put(
     *     path="/api/airplane/{id}",
     *     tags={"Airplane"},
     *     summary="Update an Airplane by Id",
     *     description="This endpoint updates the details of an existing airplane by Id.",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="The Id of the airplane to update",
     *          @OA\Schema(type="integer", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Airplane data to update. Only the fields provided will be updated.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", description="The model or name of the airplane.", example="Boeing 747"),
     *             @OA\Property(property="maximum_places", type="integer", description="The maximum seating capacity of the airplane.", example=420)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="id", type="integer", description="The unique identifier for the airplane.", example=1),
     *              @OA\Property(property="name", type="string", description="The model or name of the airplane.", example="Boeing 747"),   
     *              @OA\Property(property="maximum_places", type="integer", description="The maximum seating capacity of the airplane.", example=420),     
     *              @OA\Property(property="created_at", type="string", format="date-time", description="The timestamp when the airplane record was created.", example="2025-02-04T15:10:13.000000Z"),     
     *              @OA\Property(property="updated_at", type="string", format="date-time", description="The timestamp when the airplane record was last updated.", example="2025-02-04T15:10:13.000000Z")
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
     *             @OA\Property(property="message", type="string", example="The airplane id does not exist :(")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Unprocessable Entity",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="name", type="array",
     *                     @OA\Items(type="string", example="The name field must not be greater than 255 characters.")
     *                 ),
     *                 @OA\Property(property="maximum_places", type="array", 
     *                     @OA\Items(type="string", example="The maximum places field must be at least 0.")
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
     * @OA\Delete(
     *     path="/api/airplane/{id}",
     *     tags={"Airplane"},
     *     summary="Delete an Airplane by Id",
     *     description="This endpoint deletes an airplane from the system by its unique Id.",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="The Id of the airplane to delete",
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
     *             @OA\Property(property="message", type="string", example="The airplane id does not exist :(")
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
