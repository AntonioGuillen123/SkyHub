<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Journey;

class Destination extends Model
{
    protected $table = 'destination';

    protected $fillable = [
        'name'
    ];

    public function journeyDepartures(){
        return $this->hasMany(Journey::class, 'departure_id');
    }

    public function journeyArrivals(){
        return $this->hasMany(Journey::class, 'arrival_id');
    }
}
