<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class Flight extends Pivot
{
    private const MIN_PLACES_TO_DEACTIVATE_FLIGHT = 0;

    protected $table = 'flight';

    protected $fillable = [
        'airplane_id',
        'journey_id',
        'state',
        'remaining_places',
        'flight_date',
        'price'
    ];

    public $incrementing = true;

    public function airplane()
    {
        return $this->belongsTo(Airplane::class);
    }

    public function journey()
    {
        return $this->belongsTo(Journey::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'booking', 'flight_id', 'user_id')->withTimestamps();
    }

    protected static function booted()
    {
        static::updating(function (Flight $flight) {
            DB::transaction(function () use ($flight) { // Funciona como una función lambda, hay que usar el use para pasar una variable del ámbito externo a el ámbito interno de la función anónima :))
                $state = $flight->state;
                $remaining_places = $flight->remaining_places;
                $createdDate = $flight->created_at;
                $updatedDate = $flight->updated_at;

                if ($state && $remaining_places == self::MIN_PLACES_TO_DEACTIVATE_FLIGHT) {
                    $flight->state = false;
                }

                if (!$state && $createdDate != $updatedDate && $remaining_places > self::MIN_PLACES_TO_DEACTIVATE_FLIGHT) { // TODO Poner condición para comprobar que la fecha no haya pasado :)
                    $flight->state = true;
                }
            });
        });

        static::created(function (Flight $flight) {
            $remaining_places = $flight->remaining_places;

            if (is_null($remaining_places)) {
                $flight->remaining_places = $flight->airplane->maximum_places;
            }
            
            $flight->save();
        });
    }

    public function isAvailable()
    {
        return $this->state;
    }
}
