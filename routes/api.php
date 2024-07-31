<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProgramController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::controller(AuthController::class)->group(function () {

    Route::post('/v1/login', 'login');
    Route::post('/v1/register/mahasiswa', 'registerMahasiswa');
    Route::post('/v1/register/siswa', 'registerSiswa');
    Route::post('/v1/register/pembimbing', 'registerPembimbing');
    Route::post('/v1/register/admin', 'registerAdmin');

    Route::post('v1/magang/login', 'loginDiff');

});

// get data without login
// berita
Route::get('v1/berita', [NewsController::class, 'searchBerita']);
Route::get('v1/berita/{berita:slug}', [NewsController::class, 'getBeritaBySlug']);
Route::get('v1/berita/{berita:slug}/komentar', [CommentController::class, 'getComment']);
// gallery
Route::get('v1/gallery', [GalleryController::class, 'getImages']);
Route::get('v1/gallery/{gallery:id}',[GalleryController::class, 'getImageById']);
// program
Route::get('v1/program', [ProgramController::class, 'getPrograms']);
Route::get('v1/program/{program:id}', [ProgramController::class, 'getProgram']);
// agenda
Route::get('v1/agenda', [AgendaController::class, 'getAgendas']);
Route::get('v1/agenda/{slug}', [AgendaController::class, 'getAgenda']);

Route::middleware('auth:sanctum')->group(function () {

    Route::middleware('role:mahasiswa,siswa')->group(function () {
        Route::controller(DokumenController::class)->group(function () {
            Route::post('v1/document', 'storeDocument');
            Route::get('v1/document', 'getDocument');
            Route::delete('v1/document', 'deleteAllDocuments');
            Route::post('v1/document/update', 'updateDocument');
        });

        Route::controller(AbsenController::class)->group(function(){
            Route::get("v1/absen/magang", 'getAbsen');
            Route::post("v1/absen/jam-masuk", "tambahJamMasuk");
            Route::post("v1/absen/jam-pulang", "tambahJamPulang");
        });

    });
    
    // 
    
    // 
    Route::middleware('role:pembimbing,mahasiswa')->group(function(){
        
        Route::controller(AbsenController::class)->group(function(){
            
            Route::get('v1/absen', 'getAllAbsenMagang'); 
            Route::get('v1/absen/{user_id}', 'getAbsenMagang');
            Route::post('v1/absen/terima-absen/{absen:id}', 'terimaAbsen');
            Route::post('v1/absen/tolak-absen/{absen:id}', 'tolakAbsen');
        
        });

    });

    Route::middleware('role:admin')->group(function () {
        Route::controller(NewsController::class)->group(function () {
            Route::post('v1/berita', 'storeBerita');
            Route::post('v1/berita/{berita:slug}', 'updateBerita');
            Route::delete('v1/berita/{berita:slug}', 'deleteBerita');
        });

        Route::controller(GalleryController::class)->group(function(){
            Route::post('v1/gallery', 'storeImage');
            Route::post('v1/gallery/{gallery:id}', 'updateImage');
            Route::delete('v1/gallery/{gallery:id}', 'deleteImage');
        });

        Route::controller(ProgramController::class)->group(function(){
            Route::post('v1/program', 'addProgram');
            Route::post('v1/program/{program:id}', 'updateProgram');
            Route::delete('v1/program/{program:id}', 'deleteProgram');
        });

        Route::controller(AgendaController::class)->group(function(){
            Route::post('v1/agenda', 'addAgenda');
            Route::post('v1/agenda/{slug}', 'updateAgenda');
            Route::delete('v1/agenda/{slug}', 'deleteAgenda');
        });


    });

    Route::controller(CommentController::class)->group(function(){
        Route::post('v1/berita/{berita:slug}/komentar', 'addComment');
        Route::put('v1/berita/komentar/{komentar:id}', 'updateComment');
        Route::delete('v1/berita/komentar/{komentar:id}', 'deleteComment');
    });

    Route::delete('/v1/logout', [AuthController::class, 'logout']);
});
