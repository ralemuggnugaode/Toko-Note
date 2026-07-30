<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\stokBarangController;
use App\Http\Controllers\CatatanMasukController;
use App\Http\Controllers\CatatanKeluarController;
use App\Http\Controllers\PageController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/home', [App\Http\Controllers\PageController::class, 'index'])->name('page.home');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::resource('stok-barang-719', stokBarangController::class); 
    Route::resource('catatan-masuk', CatatanMasukController::class);
    Route::resource('catatan-keluar-742', CatatanKeluarController::class);
});
