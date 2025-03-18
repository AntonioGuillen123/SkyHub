<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Destination;
use App\Models\Airplane;
use App\Models\Flight;

class Journey extends Model
{
    protected $table = 'journey';

    protected $fillable = [
        'departure_id',
        'arrival_id'
    ];

    public function destinationDeparture(){
        return $this->belongsTo(Destination::class, 'departure_id');
    }

    public function destinationArrival(){
        return $this->belongsTo(Destination::class, 'arrival_id');
    }

    public function airplanes()
    {
        return $this->belongsToMany(Airplane::class, 'flight')
            ->using(Flight::class)
            ->withPivot('flight_date', 'arrival_date', 'state', 'remaining_places')
            ->as('flight')
            ->withTimestamps();
    }
}
