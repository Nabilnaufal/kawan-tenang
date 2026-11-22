<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Autentikasi
Route::get('/masuk', [AuthController::class, 'formMasuk'])->name('masuk');
Route::get('/daftar', [AuthController::class, 'formDaftar'])->name('daftar');
Route::view('/lupa-sandi', 'auth.lupa-sandi')->name('lupa-sandi');
Route::post('/masuk', [AuthController::class, 'masuk'])->name('masuk.store');
Route::post('/masuk/wajah', [AuthController::class, 'masukWajah'])->middleware('throttle:10,1')->name('masuk.wajah');
Route::post('/daftar', [AuthController::class, 'daftar'])->middleware('throttle:10,1')->name('daftar.store');
Route::post('/keluar', [AuthController::class, 'keluar'])->name('keluar');
Route::post('/lupa-sandi', fn () => redirect()->route('masuk'))->name('lupa-sandi.store');

// Area pengguna
Route::view('/', 'home')->name('home');
Route::view('/bubble', 'bubble')->name('bubble');
Route::view('/konseling', 'konseling.index')->name('konseling.index');
Route::view('/konseling/{id}', 'konseling.show')->name('konseling.show');
Route::view('/verifikasi', 'verifikasi')->name('verifikasi');
Route::view('/dasbor', 'dasbor.pengguna')->name('dasbor');
Route::view('/profil', 'profil', ['role' => 'pengguna'])->name('profil');

// Area konselor
Route::prefix('konselor')->name('konselor.')->group(function () {
    Route::view('/', 'konselor.dasbor')->name('dasbor');
    Route::view('/sesi', 'konselor.sesi')->name('sesi');
    Route::view('/sesi/{id}', 'konselor.sesi-show')->name('sesi.show');
    Route::view('/profil', 'profil', ['role' => 'konselor'])->name('profil');
});

// Area administrator
Route::prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'admin.dasbor')->name('dasbor');
    Route::view('/pengguna', 'admin.pengguna')->name('pengguna');
    Route::view('/laporan', 'admin.laporan')->name('laporan');
    Route::view('/verifikasi', 'admin.verifikasi')->name('verifikasi');
    Route::view('/profil', 'profil', ['role' => 'admin'])->name('profil');
});
