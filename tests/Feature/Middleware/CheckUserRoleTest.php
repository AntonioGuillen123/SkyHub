<?php

namespace Tests\Feature\Middleware;

use App\Http\Middleware\CheckUserRole;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CheckUserRoleTest extends TestCase
{
    use RefreshDatabase;

    private function createTestRoute(string $role, int $status)
    {
        Route::middleware('checkRole:' . $role)
            ->get('/test', function () use ($status) {
                return response('Ok', $status);
            });
    }

    private function authenticate()
    {
        $user = User::find(1);

        Passport::actingAs(
            $user
        );
    }


    public function test_CheckIfMiddlewareIsSuccess()
    {
        $this->seed(DatabaseSeeder::class);

        $this->createTestRoute('admin', 200);

        $this->authenticate();

        $response = $this->get('/test');

        $response
            ->assertStatus(200);
    }
}
