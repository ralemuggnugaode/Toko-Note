<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatatanKeluarController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CatatanMasukController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\stokBarangController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function(){
    Route::name('page.')->group(function(){
        Route::get('/',[PageController::class, 'index'])->name('home');
        Route::resource('stok-barang-719', stokBarangController::class)->parameters(['stok-barang-719' => 'stokBarang']);
        Route::resource('catatan-masuk-729', CatatanMasukController::class);
        Route::resource('catatan-keluar-742', CatatanKeluarController::class);
        Route::get('rekomendasi-stok', [RekomendasiController::class, 'index'])->name('rekomendasi.index');
        Route::get('/rekomendasi/export/{type}', [RekomendasiController::class, 'export'])->name('rekomendasi.export');
        Route::middleware('admin')->group(function(){
            Route::get('laporan', [ReportController::class, 'index'])->name('report.index');
            Route::resource('karyawan', UserController::class)->parameters(['karyawan' => 'user']);
        });
    });
});

Route::name('sign.')->group(function(){
    Route::get('login', [AuthController::class,'signIn'])->name('inView');
    Route::post('login', [AuthController::class,'signInProcc'])->name('In');
    Route::post('logout', [AuthController::class,'signOutProcc'])->name('Out');
});




