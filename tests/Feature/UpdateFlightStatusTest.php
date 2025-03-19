<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;
use Tests\TestCase;

class UpdateFlightStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_CheckIfUpdateFlightStatusWithScheduleWork(){
        $this->artisan('schedule:run')->assertSuccessful();
    }
}
