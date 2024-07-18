<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/v1/register', [AuthController::class, 'register']);
Route::post('/v1/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/v1/user', function(Request $request){
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function(){
    Route::delete('/v1/logout', [AuthController::class, 'logout']);
});