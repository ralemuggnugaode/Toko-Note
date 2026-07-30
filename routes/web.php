<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatatanKeluarController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CatatanMasukController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\stokBarangController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
<<<<<<< HEAD

// Auth Routes (Tamu)
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// App Routes (Perlu Login)
Route::middleware(['auth'])->group(function () {
    Route::name('page.')->group(function(){
        Route::get('/',[PageController::class, 'index'])->name('home');
    });

    Route::resource('stok-barang-719', stokBarangController::class);
    Route::resource('catatan-masuk-729', CatatanMasukController::class);
    Route::resource('catatan-keluar-742', CatatanKeluarController::class);
});
=======
Route::middleware(['auth'])->group(function(){
    Route::name('page.')->group(function(){
        Route::get('/',[PageController::class, 'index'])->name('home');
        Route::resource('stok-barang-719', stokBarangController::class)->parameters(['stok-barang-719' => 'stokBarang']);
        Route::resource('catatan-masuk-729', CatatanMasukController::class);
        Route::resource('catatan-keluar-742', CatatanKeluarController::class);
    });
});

Route::name('sign.')->group(function(){
    Route::get('Login', [AuthController::class,'signIn'])->name('inView');
});




>>>>>>> 43773f0907c635af242290f113bc7a48de03194f
