<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Airplane;
use Illuminate\Http\Request;

class AirplaneController extends Controller
{
    /**
     * Display a listing of the resource.
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

        $airplane = Airplane::create([
            'name' => $validated['name'],
            'maximum_places' => $validated['maximum_places']
        ]);

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

        $airplane->update([
            'name' => $validated['name'] ?? $airplane->name,
            'maximum_places' => (int)($validated['maximum_places'] ?? $airplane->maximum_places)
        ]);

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
