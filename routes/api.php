<?php

use App\Http\Controllers\Api\AirplaneController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FlightController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:api')->get('/user', [AuthController::class, 'showUser'])->name('apiShowUser');
Route::post('/register', [AuthController::class, 'register'])->name('apiRegister');
Route::post('/login', [AuthController::class, 'login'])->name('apiLogin');
Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout'])->name('apiLogout');
Route::middleware('signed')->get('/email/verify/{id}/{email}', [AuthController::class, 'verifyEmail'])->name('apiVerifyEmail');
Route::middleware('auth:api')->post('/email/resend', [AuthController::class, 'resendEmail'])->name('apiResendEmail');
Route::post('/password/forgot', [AuthController::class, 'forgotPassword'])->name('apiForgotPassword');
Route::post('/password/reset', [AuthController::class, 'forgotPassword'])->name('apiResetPassword');

Route::prefix('airplane')
    ->middleware(['auth:api', 'scope:manage-airplanes', 'checkRole:admin'])
    ->controller(AirplaneController::class)
    ->group(function () {
        Route::get('/', 'index')->name('apiIndexAirplane');
        Route::get('/{id}', 'show')->name('apiShowAirplane');
        Route::post('/', 'store')->name('apiStoreAirplane');
        Route::put('/{id}', 'update')->name('apiUpdateAirplane');
        Route::delete('/{id}', 'destroy')->name('apiDestroyAirplane');
    });



Route::get('/flight', [FlightController::class, 'index'])->name('apiIndexFlight');
Route::get('/flight/{id}', [FlightController::class, 'show'])->name('apiShowFlight');
Route::post('/flight', [FlightController::class, 'store'])->name('apiStoreFlight');
Route::put('/flight/{id}', [FlightController::class, 'update'])->name('apiUpdateFlight');
Route::delete('/flight/{id}', [FlightController::class, 'destroy'])->name('apiDestroyFlight');
