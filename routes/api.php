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
                Route::get('/', 'showUser')
                    ->middleware(['auth:api', 'verified', 'throttle:100,1'])
                    ->name('apiShowUser');

                Route::post('/register', 'register')
                    ->middleware('throttle:5,1')
                    ->name('apiRegister');

                Route::post('/login', 'login')
                    ->middleware('throttle:5,1')
                    ->name('apiLogin');

                Route::post('/logout', 'logout')
                    ->middleware(['auth:api', 'throttle:30,1'])
                    ->name('apiLogout');
            });

        Route::prefix('email')
            ->group(function () {
                Route::get('/verify/{id}/{hash}', 'verifyEmail')
                    ->middleware(['signed', 'throttle:10,1'])
                    ->name('apiVerifyEmail');

                Route::post('/resend', 'resendEmail')
                    ->middleware(['auth:api', 'throttle:3,5'])
                    ->name('apiResendEmail');
            });

        Route::prefix('password')
            ->middleware('verified')
            ->group(function () {
                Route::post('/forgot', 'forgotPassword')
                    ->middleware('throttle:3,5')
                    ->name('apiForgotPassword');

                Route::post('/reset/{id}/{hash}', 'resetPassword')
                    ->middleware(['signed', 'throttle:5,1'])
                    ->name('apiResetPassword');
            });
    });

Route::prefix('airplane')
    ->middleware(['auth:api', 'scope:manage-airplanes', 'checkRole:admin'])
    ->controller(AirplaneController::class)
    ->group(function () {
        Route::get('/', 'index')
            ->middleware('throttle:60,1')
            ->name('apiIndexAirplane');

        Route::get('/{id}', 'show')
            ->middleware('throttle:60,1')
            ->name('apiShowAirplane');

        Route::post('/', 'store')
            ->middleware('throttle:10,1')
            ->name('apiStoreAirplane');

        Route::put('/{id}', 'update')
            ->middleware('throttle:10,1')
            ->name('apiUpdateAirplane');

        Route::delete('/{id}', 'destroy')
            ->middleware('throttle:10,1')
            ->name('apiDestroyAirplane');
    });



Route::get('/flight', [FlightController::class, 'index'])->name('apiIndexFlight');
Route::get('/flight/{id}', [FlightController::class, 'show'])->name('apiShowFlight');
Route::post('/flight', [FlightController::class, 'store'])->name('apiStoreFlight');
Route::put('/flight/{id}', [FlightController::class, 'update'])->name('apiUpdateFlight');
Route::delete('/flight/{id}', [FlightController::class, 'destroy'])->name('apiDestroyFlight');
