<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReservationAvailabilityController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/reservations/available-times', [ReservationAvailabilityController::class, 'availableTimes'])->name('api.reservations.available-times');
    Route::post('/reservations/available-durations', [ReservationAvailabilityController::class, 'availableDurations'])->name('api.reservations.available-durations');
    Route::post('/reservations/available-people', [ReservationAvailabilityController::class, 'availablePeople'])->name('api.reservations.available-people');
});
