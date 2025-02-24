<?php

use App\Http\Controllers\Api\AirplaneController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FlightController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::prefix('user')
            ->group(function () {
                Route::get('/', 'showUser')->middleware(['auth:api', 'verified'])->name('apiShowUser');
                Route::post('/register', 'register')->name('apiRegister');
                Route::post('/login', 'login')->name('apiLogin');
                Route::post('/logout', 'logout')->middleware('auth:api')->name('apiLogout');
            });

        Route::prefix('email')
            ->group(function () {
                Route::get('/verify/{id}/{hash}', 'verifyEmail')->middleware('signed')->name('apiVerifyEmail');
                Route::post('/resend', 'resendEmail')->middleware('auth:api')->name('apiResendEmail');
            });

        Route::prefix('password')
            ->middleware('verified')
            ->group(function () {
                Route::post('/forgot', 'forgotPassword')->name('apiForgotPassword');
                Route::post('/reset/{id}/{hash}', 'resetPassword')->middleware('signed')->name('apiResetPassword');
            });
    });

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
