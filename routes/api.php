<?php

use App\Http\Controllers\AbsenController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AgendaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProgramController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\MagangController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\VisitController;
Route::prefix('v1')->group(function(){
    
    Route::controller(AuthController::class)->group(function () {
    
        Route::post('login', 'login');
        Route::post('register/magang', 'registerMagang');
        Route::post('register/pembimbing', 'registerPembimbing');
        Route::post('register/admin', 'registerAdmin');
    
        Route::post('magang/login', 'loginDiff');
    
    });
    
    // get data without login
    // berita
    Route::get('berita', [NewsController::class, 'searchBerita']);
    Route::get('berita/{berita:slug}', [NewsController::class, 'getBeritaBySlug']);
    Route::get('berita/{berita:slug}/komentar', [CommentController::class, 'getComment']);
    // gallery
    Route::get('gallery', [GalleryController::class, 'getImages']);
    Route::get('gallery/{gallery:id}',[GalleryController::class, 'getImageById']);
    // program
    Route::get('program', [ProgramController::class, 'getPrograms']);
    Route::get('program/{program:id}', [ProgramController::class, 'getProgram']);
    // agenda
    Route::get('agenda', [AgendaController::class, 'getAgendas']);
    Route::get('agenda/{slug}', [AgendaController::class, 'getAgenda']);

    Route::post('log-visit', [VisitController::class, 'logVisit']);
    Route::get('online-status', [VisitController::class, 'updateOnlineStatus']);
    Route::get('statistics', [VisitController::class, 'getStatistics']);

    
    
    Route::middleware('auth:sanctum')->group(function () {
    
        Route::middleware('role:magang')->group(function () {
    
            Route::controller(AbsenController::class)->group(function(){
                Route::get("absen/magang", 'getAbsen');
                Route::post("absen/jam-masuk", "tambahJamMasuk");
                Route::post("absen/jam-pulang", "tambahJamPulang");
            });
            Route::controller(LaporanController::class)->group(function () {
                Route::post("laporan", "addLaporan");
                Route::get("laporan/magang", 'getLaporan');
                Route::put("laporan", 'updateLaporan');
                Route::delete("laporan", 'deleteLaporan');
            });
    
    
        });
        
        // 
        
        // 
        Route::middleware('role:pembimbing,admin')->group(function(){
            
            Route::controller(AbsenController::class)->group(function(){
                
                Route::get('absen', 'getAllAbsenMagang'); 
                Route::get('absen/{user_id}', 'getAbsenMagang');
                Route::post('absen/terima-absen/{absen:id}', 'terimaAbsen');
                Route::post('absen/tolak-absen/{absen:id}', 'tolakAbsen');
            
            });

            Route::controller(MagangController::class)->group(function(){
                
                Route::get('user-magang', 'getAllUserMagang');
                Route::get('user-magang/{userMagang:id_user}', 'getMagangByUserId');
                Route::put('user-magang/terima/{userMagang:id}', 'acceptMagang');
                Route::put('user-magang/tolak/{userMagang:id}', 'rejectMagang');
            });
    
            Route::controller(LaporanController::class)->group(function(){
                Route::get("laporan", "getAllLaporan");
            });
    
        });
    
        Route::middleware('role:admin')->group(function () {
            Route::controller(NewsController::class)->group(function () {
                Route::post('berita', 'storeBerita');
                Route::post('berita/{berita:slug}', 'updateBerita');
                Route::delete('berita/{berita:slug}', 'deleteBerita');
            });
    
            Route::controller(GalleryController::class)->group(function(){
                Route::post('gallery', 'storeImage');
                Route::post('gallery/{gallery:id}', 'updateImage');
                Route::delete('gallery/{gallery:id}', 'deleteImage');
            });
    
            Route::controller(ProgramController::class)->group(function(){
                Route::post('program', 'addProgram');
                Route::post('program/{program:id}', 'updateProgram');
                Route::delete('program/{program:id}', 'deleteProgram');
            });
    
            Route::controller(AgendaController::class)->group(function(){
                Route::post('agenda', 'addAgenda');
                Route::post('agenda/{slug}', 'updateAgenda');
                Route::delete('agenda/{slug}', 'deleteAgenda');
            });
    
            Route::controller(AuthController::class)->group(function () {
                Route::get('pembimbing', 'getAllPembimbing');
                Route::delete('pembimbing/{id}', 'deletePembimbingById');
            });


        });
    
        Route::controller(CommentController::class)->group(function(){
            Route::post('berita/{berita:slug}/komentar', 'addComment');
            Route::put('berita/komentar/{komentar:id}', 'updateComment');
            Route::delete('berita/komentar/{komentar:id}', 'deleteComment');
        });
    
        
        Route::delete('logout', [AuthController::class, 'logout']);
    });

    
    
});

Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    Route::get('/kegiatan', [KegiatanController::class, 'index']);
    Route::post('/kegiatan', [KegiatanController::class, 'store']);
    Route::get('/kegiatan/{id}', [KegiatanController::class, 'show']);
    Route::put('/kegiatan/{id}', [KegiatanController::class, 'update']);
    Route::delete('/kegiatan/{id}', [KegiatanController::class, 'destroy']);
    Route::post('/kegiatan/{id}/confirm', [KegiatanController::class, 'confirm']);
    Route::post('/kegiatan/{id}/reject', [KegiatanController::class, 'reject']);


    // PENILAIAN
    Route::get('/penilaian', [PenilaianController::class, 'index']);
    Route::post('/penilaian', [PenilaianController::class, 'store']);
    Route::get('/penilaian/{id}', [PenilaianController::class, 'show']);
    Route::put('/penilaian/{id}', [PenilaianController::class, 'update']);
    Route::delete('/penilaian/{id}', [PenilaianController::class, 'destroy']);
});