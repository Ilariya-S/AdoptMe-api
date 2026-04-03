<?php

use Illuminate\Support\Facades\Route;
use App\Services\AI\Controllers\AiController;

Route::prefix('ai')->group(function () {
    Route::post('/chat', [AiController::class, 'chat'])->middleware('throttle:15,1');
});