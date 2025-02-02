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
        return $this->belongsToMany(Journey::class)
            ->using(Flight::class)
            ->withPivot('flight_date', 'state')
            ->withTimestamps();
    }
}
