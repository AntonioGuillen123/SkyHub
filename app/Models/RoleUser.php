<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    protected $table = 'role_user';

    protected $fillable = [
        'name'
    ];

    public function destinationDeparture(){
        return $this->hasMany(User::class);
    }
}
