<?php

use Illuminate\Support\Facades\Route;
use App\Services\Donation\DonationController;

Route::post('/donate', [DonationController::class, 'generatePayment']);