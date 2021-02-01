<?php

use App\Http\Controllers\BidController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\Mechanic\MechanicProfileController;
use App\Http\Controllers\Driver\ProfileController;
use App\Http\Controllers\FulfilledJobController;
use App\Http\Controllers\FulfillJobController;
use App\Http\Controllers\Mechanic\ServiceController;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('categories')->middleware('auth:sanctum')->group(function () {
        Route::get('/', CategoryController::class)->middleware(['role:driver|mechanic']);
    });

    Route::prefix('drivers')->middleware('auth:sanctum')->group(function () {
        Route::get('{user}/profile', ProfileController::class)->middleware(['role:driver|mechanic']);

        Route::prefix('jobs')->group(function () {
            Route::post('fulfill/{job}', [FulfillJobController::class, 'store'])->middleware(['role:mechanic']);

            Route::prefix('fulfilled')->group(function () {
                Route::get('/', [FulfilledJobController::class, 'index'])->middleware(['role:driver|mechanic']);
            });

            Route::post('/', [JobController::class, 'store'])->middleware(['role:driver']);
            Route::get('{job}', [JobController::class, 'show'])->middleware(['role:driver|mechanic']);
            Route::get('active/{fulfilledJob}', [FulfilledJobController::class, 'show'])->middleware(['role:driver|mechanic']);

            Route::post('{job}/accept', [FulfilledJobController::class, 'store'])->middleware(['role:driver']);

            Route::prefix('bids')->group(function () {
                Route::get('{job}', [BidController::class, 'index'])->middleware(['role:driver|mechanic']);
                Route::post('{job}', [BidController::class, 'store'])->middleware(['role:mechanic']);
            });
        });
    });

    Route::prefix('mechanics')->middleware('auth:sanctum')->group(function () {
        Route::get('{user}/profile', MechanicProfileController::class)->middleware(['role:driver|mechanic']);
        Route::get('{user}/services', [ServiceController::class, 'index'])->middleware(['role:driver|mechanic']);
        Route::post('services', [ServiceController::class, 'store'])->middleware(['role:mechanic']);
    });

    Route::middleware('auth:sanctum')->get('user', function () {
        return new UserResource(User::with('roles')->find(Auth::id()));
    });
});
