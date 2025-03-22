<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Socialite\Facades\Socialite;
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
}
