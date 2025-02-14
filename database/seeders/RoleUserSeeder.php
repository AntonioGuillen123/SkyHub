<?php

namespace Database\Seeders;

use App\Models\RoleUser;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleUsers = [
            ['name' => 'admin'],
            ['name' => 'user']
        ];

        foreach ($roleUsers as $roleUser) {
            RoleUser::create($roleUser);
        }
    }
}
