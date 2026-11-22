<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BubbleController;
use App\Http\Controllers\CounselingController;
use App\Http\Controllers\AdminController;
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
Route::get('/bubble', [BubbleController::class, 'index'])->name('bubble');

Route::middleware('auth')->group(function () {
    Route::post('/bubble', [BubbleController::class, 'store'])->name('bubble.store');
    Route::post('/bubble/{post}/komentar', [BubbleController::class, 'komentar'])->name('bubble.komentar');
    Route::post('/bubble/{post}/suka', [BubbleController::class, 'suka'])->name('bubble.suka');
    Route::post('/bubble/{post}/lapor', [BubbleController::class, 'lapor'])->name('bubble.lapor');
});
Route::middleware('auth')->prefix('konseling')->name('konseling.')->group(function () {
    Route::get('/', [CounselingController::class, 'index'])->name('index');
    Route::post('/', [CounselingController::class, 'store'])->name('store');
    Route::get('/{session}', [CounselingController::class, 'show'])->name('show');
    Route::post('/{session}/pesan', [CounselingController::class, 'message'])->name('message');
    Route::post('/{session}/selesai', [CounselingController::class, 'close'])->name('close');
});
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
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::view('/', 'admin.dasbor')->name('dasbor');
    Route::get('/pengguna', [AdminController::class, 'pengguna'])->name('pengguna');
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
    Route::patch('/laporan/{report}', [AdminController::class, 'updateLaporan'])->name('laporan.update');
    Route::view('/verifikasi', 'admin.verifikasi')->name('verifikasi');
    Route::view('/profil', 'profil', ['role' => 'admin'])->name('profil');
});
