<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Journey;
use App\Models\Flight;

class Airplane extends Model
{
    protected $table = 'airplane';

    protected $fillable = [
        'name',
        'maximum_places'
    ];

    public function journeys()
    {
        return $this->belongsToMany(Journey::class, 'flight')
            ->using(Flight::class)
            ->withPivot('id', 'flight_date', 'arrival_date', 'state', 'remaining_places')
            ->as('flight')
            ->withTimestamps();
    }
}
