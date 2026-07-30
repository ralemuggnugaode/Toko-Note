<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatatanKeluarController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CatatanMasukController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\stokBarangController;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::middleware(Authenticate::class)->group(function(){
    // Route halaman utama (Dashboard)
    Route::get('/', [PageController::class, 'index'])->name('page.home');

    // Route resource
    Route::resource('stok-barang-719', stokBarangController::class)->parameters(['stok-barang-719' => 'stokBarang']);
    Route::resource('catatan-masuk-729', CatatanMasukController::class);
    Route::resource('catatan-keluar-742', CatatanKeluarController::class);
});

// Route Login
Route::get('Login', [AuthController::class, 'signIn'])->name('login');
Route::post('Login', [AuthController::class, 'signInProcc'])->name('sign.store');

// Route Logout (diubah namanya menjadi 'logout' agar sesuai dengan navbar.blade.php)
Route::post('Logout', [AuthController::class, 'signOutProcc'])->name('logout');
