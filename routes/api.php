<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function(){
    
    Route::post('/v1/login', 'login');
    Route::post('/v1/register/mahasiswa', 'registerMahasiswa');
    Route::post('/v1/register/siswa', 'registerSiswa');
    Route::post('/v1/register/pembimbing', 'registerPembimbing');
    
});


Route::middleware('auth:sanctum')->get('/v1/user', function(Request $request){
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function(){
    Route::delete('/v1/logout', [AuthController::class, 'logout']);
});