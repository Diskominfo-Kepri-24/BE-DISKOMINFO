<?php

use App\Http\Controllers\EmailVerificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// verify email
Route::get('verify', [EmailVerificationController::class, 'index']);