<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\RoleUser;
use App\Models\Flight;
use App\Models\AuthProvider;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use  HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_user_id',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roleUser()
    {
        return $this->belongsTo(RoleUser::class);
    }

    public function flights()
    {
        return $this->belongsToMany(Flight::class, 'booking', 'user_id', 'flight_id')->withTimestamps();
    }

    public function authProviders()
    {
        return $this->hasMany(AuthProvider::class);
    }

    public function hasRole(string $roleUser)
    {
        return $this->roleUser()->where('name', $roleUser)->exists();
    }
}
