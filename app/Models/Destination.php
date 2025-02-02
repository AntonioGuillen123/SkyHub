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
        return $this->hasMany(Journey::class);
    }

    public function journeyArrivals(){
        return $this->hasMany(Journey::class);
    }
}
