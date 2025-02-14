<?php

use App\Http\Controllers\Api\AirplaneController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FlightController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/register', [AuthController::class, 'register'])->name('apiRegister');

Route::get('/airplane', [AirplaneController::class, 'index'])->name('apiIndexAirplane');
Route::get('/airplane/{id}', [AirplaneController::class, 'show'])->name('apiShowAirplane');
Route::post('/airplane', [AirplaneController::class, 'store'])->name('apiStoreAirplane');
Route::put('/airplane/{id}', [AirplaneController::class, 'update'])->name('apiUpdateAirplane');
Route::delete('/airplane/{id}', [AirplaneController::class, 'destroy'])->name('apiDestroyAirplane');

Route::get('/flight', [FlightController::class, 'index'])->name('apiIndexFlight');
Route::get('/flight/{id}', [FlightController::class, 'show'])->name('apiShowFlight');
Route::post('/flight', [FlightController::class, 'store'])->name('apiStoreFlight');
Route::put('/flight/{id}', [FlightController::class, 'update'])->name('apiUpdateFlight');
Route::delete('/flight/{id}', [FlightController::class, 'destroy'])->name('apiDestroyFlight');