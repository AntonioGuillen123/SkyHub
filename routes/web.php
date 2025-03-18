<?php

use App\Http\Controllers\AirplaneController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\FlightController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/flight', [FlightController::class, 'index'])->name('indexFlight');
Route::post('/booking', [BookingController::class, 'store'])->middleware('auth', 'checkRole:user')->name('makeBooking');
Route::delete('/booking', [BookingController::class, 'destroy'])->middleware('auth', 'checkRole:user')->name('cancelBooking');

Route::get('/airplane', [AirplaneController::class, 'index'])->middleware('auth', 'checkRole:admin')->name('indexAirplane');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
