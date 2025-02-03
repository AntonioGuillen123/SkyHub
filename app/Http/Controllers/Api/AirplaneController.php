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
        $airplanes = Airplane::all();

        return $this->responseWithSuccess($airplanes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $airplane = Airplane::find($id);

        if(!$airplane){
            return $this->responseWithError('The airplane id does not exist', 404);
        }

        return $this->responseWithSuccess($airplane);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Airplane $airplane)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Airplane $airplane)
    {
        //
    }

    private function responseWithSuccess($data, $status = 200){
        return response()->json($data, $status);
    }

    private function responseWithError($message, $status){
        return response()->json([
            'message' => $message . ' :('
        ], $status);
    }
}
