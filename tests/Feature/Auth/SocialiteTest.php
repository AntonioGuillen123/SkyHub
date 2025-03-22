<?php

namespace Tests\Feature\Auth;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\Provider;
use Mockery;
use Tests\TestCase;

class SocialiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_redirect_function()
    {
        $provider = 'github';
        $mockProvider = Mockery::mock(Provider::class);
        $mockProvider->shouldReceive('redirect')
            ->once()
            ->andReturn(redirect()->away('https://example.com'));

        Socialite::shouldReceive('driver')
            ->once()
            ->with($provider)
            ->andReturn($mockProvider);

        $response = $this->get("/auth/{$provider}/redirect");

        $response->assertRedirect();
    }

    public function test_example(): void
    {
        $this->seed(DatabaseSeeder::class);

        $provider = 'github';

        $abstractUser = Mockery::mock(SocialiteUser::class);
        $abstractUser->shouldReceive('getId')->andReturn('123456789');
        $abstractUser->shouldReceive('getEmail')->andReturn('test@example.com');
        $abstractUser->shouldReceive('getNickname')->andReturn('testuser');

        $mockProvider = Mockery::mock(Provider::class);
        $mockProvider->shouldReceive('user')
            ->once()
            ->andReturn($abstractUser);

        Socialite::shouldReceive('driver')
            ->once()
            ->with($provider)
            ->andReturn($mockProvider);

        $response = $this->get("/auth/{$provider}/callback");

        $response->assertRedirect();
    }
}
