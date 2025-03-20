<?php

use App\Http\Controllers\AirplaneController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::prefix('booking')
    ->controller(BookingController::class)
    ->middleware(['auth', 'checkRole:user'])
    ->group(function () {
        Route::post('/', 'store')
            ->name('makeBooking');

        Route::delete('/', 'destroy')
            ->name('cancelBooking');
    });

Route::get('/flight', [FlightController::class, 'index'])->name('indexFlight');

Route::get('/airplane', [AirplaneController::class, 'index'])->middleware('auth', 'checkRole:admin')->name('indexAirplane');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
