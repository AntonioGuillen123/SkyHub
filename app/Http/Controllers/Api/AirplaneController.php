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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateData($request, 'store');

        $airplane = Airplane::create($validated)->refresh();

        return $this->responseWithSuccess($airplane, 201);
    }

    /**
     * Display the specified resource.
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
