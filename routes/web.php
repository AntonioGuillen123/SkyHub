<?php

use App\Http\Controllers\AirplaneController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialiteController;
use Illuminate\Support\Facades\Route;

Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])->name('socialiteRedirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('socialiteCallback');

Route::get('/', function () {
    return view('home');
})->name('home');

Route::prefix('booking')
    ->controller(BookingController::class)
    ->middleware(['auth', 'checkRole:user'])
    ->group(function () {
        Route::get('/', 'index')
            ->name('indexBooking');

        Route::post('/', 'store')
            ->name('makeBooking');

        Route::delete('/', 'destroy')
            ->name('cancelBooking');
    });

Route::get('/flight', [FlightController::class, 'index'])->name('indexFlight');

Route::get('/airplane', [AirplaneController::class, 'index'])->middleware('auth', 'checkRole:admin')->name('indexAirplane');

Route::prefix('profile')
    ->controller(ProfileController::class)
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', 'edit')
            ->name('profile.edit');

        Route::patch('/', 'update')
            ->name('profile.update');

        Route::delete('/', 'destroy')
            ->name('profile.destroy');
    });

require __DIR__ . '/auth.php';
