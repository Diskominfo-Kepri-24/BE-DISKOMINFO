<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function(){
    
    Route::post('/v1/login', 'login');
    Route::post('/v1/register/mahasiswa', 'registerMahasiswa');
    Route::post('/v1/register/siswa', 'registerSiswa');
    Route::post('/v1/register/pembimbing', 'registerPembimbing');
    
});


Route::middleware('auth:sanctum')->group(function(){
    
    Route::middleware('role:mahasiswa,siswa')->group(function(){
        Route::controller(DokumenController::class)->group(function(){
            Route::post('v1/document', 'storeDocument');
            Route::get('v1/document', 'getDocument');
            Route::delete('v1/document', 'deleteAllDocuments');
            Route::post('v1/document/update', 'updateDocument');
            
        });
    });

    Route::middleware('role:admin')->group(function(){
        Route::controller(NewsController::class)->group(function(){
            Route::post('v1/berita', 'storeBerita');
            Route::get('v1/berita', 'searchBerita');
            Route::get('v1/berita/{slug}', 'getBeritaBySlug');
            Route::post('v1/berita/{slug}', 'updateBerita');
            Route::delete('v1/berita/{slug}', 'deleteBerita');


        });

    });

    Route::delete('/v1/logout', [AuthController::class, 'logout']);


});
