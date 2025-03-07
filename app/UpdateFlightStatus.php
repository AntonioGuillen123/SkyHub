<?php

namespace App;

use App\Models\Flight;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UpdateFlightStatus
{
    public function __invoke()
    {
        Flight::where('state', true)
            ->where('flight_date', '<', Carbon::now())
            ->update(['state' => false]);

        Log::info('Flight status updated successfully :)');
    }
}
