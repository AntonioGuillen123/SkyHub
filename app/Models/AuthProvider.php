<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class AuthProvider extends Model
{
    protected $table = 'auth_provider';

    protected $fillable = [
        'user_id',
        'provider_id',
        'provider_name',
        'nickname',
        'login_at'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
