<?php

namespace App\Http\Controllers;

use App\Models\Airplane;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Http\Request;

class AirplaneController extends Controller
{
    public function index()
    {
        $flights = $this->getFlightWithRelationShips();

        $airplanes = $this->groupFlightsByAirplane($flights);

        return $this->responseWithSuccess($airplanes);
    }

    private function getFlightWithRelationShips()
    {
        return Flight::with(['users', 'journey', 'journey.destinationDeparture', 'journey.destinationArrival'])
            ->get();
    }

    private function groupFlightsByAirplane(mixed $flights)
    {
        return $flights->groupBy(['airplane.name', 'airplane.maximum_places']);
    }

    private function responseWithSuccess(mixed $airplanes)
    {
        return view('airplane', compact('airplanes'));
    }
}
