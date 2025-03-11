<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoleUser extends Model
{
    use HasFactory;

    protected $table = 'role_user';

    protected $fillable = [
        'name'
    ];

    public function users(){
        return $this->hasMany(User::class);
    }
}
