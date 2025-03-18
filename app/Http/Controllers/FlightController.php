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
    public function index(Request $request)
    {
        /* $flights = Flight::orderBy('flight_date', 'asc')
            ->with(['airplane', 'journey', 'journey.destinationDeparture', 'journey.destinationArrival'])
            ->get(); */
        /* $flights = Flight::orderBy('flight_date', 'asc')
            ->with(['airplane', 'journey', 'journey.destinationDeparture', 'journey.destinationArrival'])
            ->find(1); */

        $queryParams = $this->getQueryParams($request);
        $userId = $this->getUserId();

        /*  $flights = Flight::orderBy('flight_date', 'asc')
            ->withCount(['users as booked' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->with(['airplane', 'journey', 'journey.destinationDeparture', 'journey.destinationArrival'])
            ->get(); */ // Todos

        /* $flights = Flight::orderBy('flight_date', 'asc')
            ->withCount(['users as booked' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->with(['airplane', 'journey', 'journey.destinationDeparture', 'journey.destinationArrival'])
            ->get(); */
        $flights = $this->getFlights($userId, $queryParams);


        /* $booking = $user->flights()->find($flights->id); */

        // return response()->json($flights);

        return view('flight', compact('flights'));
    }

    private function getFlights(int $userId, array|null $queryParams)
    {
        $queryFlights = $this->getAllFlights($userId);

        if (!is_null($queryParams)) {
            $queryFlights = $this->getFlightsWithFilters($queryFlights, $queryParams);
        }

        return $queryFlights->get();
    }

    private function getFlightsWithFilters(mixed $queryFlights, array $queryParams)
    {
        if (array_key_exists('state', $queryParams)) {
            $queryFlights = $this->getFlightsByState($queryFlights, $queryParams['state']);
        }

        if (array_key_exists('empty', $queryParams)) {
            $queryFlights = $this->getFlightsByEmpty($queryFlights, $queryParams['empty']);
        }

        if (array_key_exists('passed', $queryParams)) {
            $queryFlights = $this->getFlightsByPassed($queryFlights, $queryParams['passed']);
        }

        return $queryFlights;
    }

    private function getFlightsByState(mixed $queryFlights, string $value)
    {
        return $queryFlights->where('state', $value);
    }

    private function getFlightsByEmpty(mixed $queryFlights, string $value)
    {
        $option = $value ? '=' : '>';

        return $queryFlights->where('remaining_places', $option, 0);
    }

    private function getFlightsByPassed(mixed $queryFlights, string $value)
    {
        $option = $value ? '<' : '>=';

        return $queryFlights->where('flight_date', $option, now());
    }

    private function getAllFlights(int $userId)
    {
        return Flight::orderBy('flight_date', 'asc')
            ->withCount(['users as booked' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->with(['airplane', 'journey', 'journey.destinationDeparture', 'journey.destinationArrival']);
    }

    private function getQueryParams(Request $request)
    {
        return $request->query();
    }

    private function getUserId()
    {
        $user = $this->getUserFromAuth();

        return $user->id ?? 0;
    }

    private function getUserFromAuth()
    {
        return Auth::user();
    }
}
