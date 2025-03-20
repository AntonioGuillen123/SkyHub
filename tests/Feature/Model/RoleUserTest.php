<?php

namespace Tests\Feature\Model;

use App\Models\RoleUser;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_CheckIfRoleUserHasUsersRelationship(){
        $this->seed(DatabaseSeeder::class);

        $roleUser = RoleUser::find(1);

        $this->assertInstanceOf(User::class, $roleUser->users[0]);
    }
}
