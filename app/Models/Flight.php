<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $table = 'flight';

    protected $fillable = [
        'airplane_id',
        'journey_id',
        'flight_date',
        'state'
    ];

    public function airplane(){
        return $this->belongsTo(Airplane::class);
    }

    public function journey(){
        return $this->belongsTo(Journey::class);
    }
}
