<?php

use Illuminate\Support\Facades\Route;
use App\Services\Pets\Controllers\PetController;
use App\Services\Pets\Controllers\ApplicationController;

Route::prefix('pets')->group(function () {
    Route::get('/', [PetController::class, 'index']);
    Route::get('/{id}', [PetController::class, 'show']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/applications', [ApplicationController::class, 'store']);

    Route::post('/pets', [PetController::class, 'store']);
    Route::put('/pets/{id}', [PetController::class, 'update']);
    Route::delete('/pets/{id}', [PetController::class, 'destroy']);
    Route::patch('/applications/{id}/approve', [ApplicationController::class, 'approve']);
    Route::patch('/applications/{id}/reject', [ApplicationController::class, 'reject']);
    Route::get('/applications/my', [ApplicationController::class, 'myApplications']);
    Route::get('/applications', [ApplicationController::class, 'index']);
    Route::delete('/applications/{id}', [ApplicationController::class, 'destroy']);
});