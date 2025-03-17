<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /* $flights = Flight::orderBy('flight_date', 'asc')
            ->with(['airplane', 'journey', 'journey.destinationDeparture', 'journey.destinationArrival'])
            ->get(); */
        /* $flights = Flight::orderBy('flight_date', 'asc')
            ->with(['airplane', 'journey', 'journey.destinationDeparture', 'journey.destinationArrival'])
            ->find(1); */
        $user = $this->getUserFromAuth();
        $userId = $user->id ?? 0;

        $flights = Flight::orderBy('flight_date', 'asc')
            ->withCount(['users as booked' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->with(['airplane', 'journey', 'journey.destinationDeparture', 'journey.destinationArrival'])
            ->get();


        /* $booking = $user->flights()->find($flights->id); */

        // return response()->json($flights);

        return view('flight', compact('flights'));
    }

    private function getUserFromAuth()
    {
        return Auth::user();
    }
}
