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

    private function createTestRoute(string $role, string $route, string $message, int $status)
    {
        Route::middleware('checkRole:' . $role)
            ->get($route, function () use ($message, $status) {
                return response($message, $status);
            });
    }

    private function authenticate()
    {
        $user = User::find(1);

        Passport::actingAs(
            $user
        );
    }

    public function test_CheckIfMiddlewareIsWebSuccess()
    {
        $this->seed(DatabaseSeeder::class);

        $messageTest = 'OK';

        $route = '/test';

        $statusTest = 200;

        $this->createTestRoute('admin', $route, $messageTest, $statusTest);

        $this->authenticate();

        $response = $this->get($route);

        $response
            ->assertStatus($statusTest)
            ->assertContent($messageTest);
    }

    public function test_CheckIfMiddlewareIsSuccess()
    {
        $this->seed(DatabaseSeeder::class);

        $messageTest = 'OK';

        $route = '/api/test';

        $statusTest = 200;

        $this->createTestRoute('admin', $route, $messageTest, $statusTest);

        $this->authenticate();

        $response = $this->get($route);

        $response
            ->assertStatus($statusTest)
            ->assertContent($messageTest);
    }

    public function test_CheckIfMiddlewareIsWrong()
    {
        $this->seed(DatabaseSeeder::class);

        $messageTest = [
            'message' => 'Access denied. You don´t have permissions to do that'
        ];

        $route = '/api/test';

        $statusTest = 403;

        $this->createTestRoute('user', $route, $messageTest['message'], $statusTest);

        $this->authenticate();

        $response = $this->get($route);

        $response
            ->assertStatus($statusTest)
            ->assertJsonFragment($messageTest);
    }
}
