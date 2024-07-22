<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DokumenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function(){
    
    Route::post('/v1/login', 'login');
    Route::post('/v1/register/mahasiswa', 'registerMahasiswa');
    Route::post('/v1/register/siswa', 'registerSiswa');
    Route::post('/v1/register/pembimbing', 'registerPembimbing');
    
});

Route::middleware(['auth:sanctum', 'role'])->group(function(){

    Route::controller(DokumenController::class)->group(function(){
        Route::post('v1/document', 'storeDocument');
        Route::get('v1/document', 'getDocument');
        Route::delete('v1/document', 'deleteAllDocuments');
        Route::delete('v1/document/{documentName}/name/{fileName}', 'deleteDocument');
        Route::put('v1/document', 'updateDocument');
    });

    Route::get('v1/get-document', [DokumenController::class, 'getDocument']);

    Route::delete('/v1/logout', [AuthController::class, 'logout']);
});