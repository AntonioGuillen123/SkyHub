<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\User;

class Flight extends Pivot
{
    private const MIN_PLACES_TO_DEACTIVATE_FLIGHT = 0;

    protected $table = 'flight';

    protected $fillable = [
        'airplane_id',
        'journey_id',
        'state',
        'remaining_places',
        'flight_date'
    ];

    public $incrementing = true;

    public function airplane(){
        return $this->belongsTo(Airplane::class);
    }

    public function journey(){
        return $this->belongsTo(Journey::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'booking')->withTimestamps();
    }

    protected static function booted(){
        static::updating(function (Flight $flight){
            $state = $flight->state;
            $remaining_places = $flight->remaining_places;
            
            if ($state && $remaining_places == self::MIN_PLACES_TO_DEACTIVATE_FLIGHT){
                $flight->state = false;
            }
        });
    }
}
