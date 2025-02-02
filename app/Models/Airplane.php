<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Flight;

class Airplane extends Model
{
    protected $table = 'airplane';

    protected $fillable = [
        'name',
        'maximum_places'
    ];

    public function flights(){
        return $this->hasMany(Flight::class);
    }
}
