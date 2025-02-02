<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Destination;

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
}
