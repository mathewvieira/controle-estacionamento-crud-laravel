<?php

use App\Http\Controllers\Api\VehicleController;
use Illuminate\Support\Facades\Route;

Route::prefix('vehicles')->name('vehicles.')->group(function () {
    Route::get('{id}', [VehicleController::class, 'show'])->name('show');

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [VehicleController::class, 'index'])->name('index');
        Route::post('/', [VehicleController::class, 'store'])->name('store');
        Route::match(['put', 'patch'], '{id}', [VehicleController::class, 'update'])->name('update');
        Route::delete('{id}', [VehicleController::class, 'destroy'])->name('destroy');
    });
});
