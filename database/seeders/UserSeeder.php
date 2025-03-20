<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('P@ssw0rd'),
                'role_user_id' => 1,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('P@ssw0rd'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Samuel Green',
                'email' => 'samuel@example.com',
                'password' => Hash::make('P@ssw0rd'),
                'role_user_id' => 1,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Emily White',
                'email' => 'emily@example.com',
                'password' => Hash::make('P@ssw0rd'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'David Black',
                'email' => 'david@example.com',
                'password' => Hash::make('P@ssw0rd'),
                'role_user_id' => 1,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Michael Brown',
                'email' => 'michael@example.com',
                'password' => Hash::make('P@ssw0rd'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Sarah Blue',
                'email' => 'sarah@example.com',
                'password' => Hash::make('P@ssw0rd'),
                'role_user_id' => 1,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Daniel Lee',
                'email' => 'daniel@example.com',
                'password' => Hash::make('P@ssw0rd'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'Sophie Taylor',
                'email' => 'sophie@example.com',
                'password' => Hash::make('P@ssw0rd'),
                'role_user_id' => 1,
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ],
            [
                'name' => 'James Harris',
                'email' => 'james@example.com',
                'password' => Hash::make('P@ssw0rd'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        ];
        
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
