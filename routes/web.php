<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatatanKeluarController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CatatanMasukController;
use App\Http\Controllers\stokBarangController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function(){
    Route::name('page.')->group(function(){
        Route::get('/',[PageController::class, 'index'])->name('home');
        Route::resource('stok-barang-719', stokBarangController::class)->parameters(['stok-barang-719' => 'stokBarang']);
        Route::resource('karyawan', UserController::class)->parameters(['karyawan' => 'user']);
        Route::resource('catatan-masuk-729', CatatanMasukController::class);
        Route::resource('catatan-keluar-742', CatatanKeluarController::class);
    });
});

Route::name('sign.')->group(function(){
    Route::get('login', [AuthController::class,'signIn'])->name('inView');
    Route::post('login', [AuthController::class,'signInProcc'])->name('In');
    Route::post('logout', [AuthController::class,'signOutProcc'])->name('Out');
});




