<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatatanKeluarController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CatatanMasukController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\stokBarangController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
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




