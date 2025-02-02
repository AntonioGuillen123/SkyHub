<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\User;

class Flight extends Pivot
{
    protected $table = 'flight';

    protected $fillable = [
        'airplane_id',
        'journey_id',
        'flight_date',
        'state'
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
}
