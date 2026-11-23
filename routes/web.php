<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BubbleController;
use App\Http\Controllers\CounselingController;
use App\Http\Controllers\CounselorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Autentikasi
Route::get('/masuk', [AuthController::class, 'formMasuk'])->name('masuk');
Route::redirect('/login', '/masuk')->name('login');
Route::get('/daftar', [AuthController::class, 'formDaftar'])->name('daftar');
Route::view('/lupa-sandi', 'auth.lupa-sandi')->name('lupa-sandi');
Route::post('/masuk', [AuthController::class, 'masuk'])->name('masuk.store');
Route::post('/masuk/wajah', [AuthController::class, 'masukWajah'])->middleware('throttle:10,1')->name('masuk.wajah');
Route::post('/daftar', [AuthController::class, 'daftar'])->middleware('throttle:10,1')->name('daftar.store');
Route::post('/keluar', [AuthController::class, 'keluar'])->name('keluar');
Route::post('/lupa-sandi', fn () => redirect()->route('masuk'))->name('lupa-sandi.store');

// Area pengguna
Route::get('/', HomeController::class)->name('home');
Route::get('/bubble', [BubbleController::class, 'index'])->name('bubble');

Route::middleware('auth')->group(function () {
    Route::post('/bubble', [BubbleController::class, 'store'])->name('bubble.store');
    Route::post('/bubble/{post}/komentar', [BubbleController::class, 'komentar'])->name('bubble.komentar');
    Route::post('/bubble/{post}/suka', [BubbleController::class, 'suka'])->name('bubble.suka');
    Route::post('/bubble/{post}/lapor', [BubbleController::class, 'lapor'])->name('bubble.lapor');
    Route::view('/verifikasi', 'verifikasi')->name('verifikasi');
    Route::get('/dasbor', [DashboardController::class, 'index'])->name('dasbor');
    Route::get('/profil', [ProfileController::class, 'show'])->name('profil');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profil.update');
    Route::delete('/profil/wajah', [ProfileController::class, 'forgetFace'])->name('profil.wajah.destroy');
});
Route::middleware('auth')->prefix('konseling')->name('konseling.')->group(function () {
    Route::get('/', [CounselingController::class, 'index'])->name('index');
    Route::post('/', [CounselingController::class, 'store'])->name('store');
    Route::get('/{session}', [CounselingController::class, 'show'])->name('show');
    Route::post('/{session}/pesan', [CounselingController::class, 'message'])->name('message');
    Route::post('/{session}/selesai', [CounselingController::class, 'close'])->name('close');
});
// Area konselor
Route::middleware(['auth', 'role:konselor'])->prefix('konselor')->name('konselor.')->group(function () {
    Route::get('/', [CounselorController::class, 'dashboard'])->name('dasbor');
    Route::get('/sesi', [CounselorController::class, 'sessions'])->name('sesi');
    Route::get('/sesi/{session}', [CounselingController::class, 'show'])->name('sesi.show');
    Route::post('/sesi/{session}/catatan', [CounselorController::class, 'note'])->name('sesi.catatan');
    Route::patch('/ketersediaan', [CounselorController::class, 'availability'])->name('ketersediaan');
    Route::get('/profil', [ProfileController::class, 'show'])->name('profil');
});

// Area administrator
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dasbor');
    Route::get('/pengguna', [AdminController::class, 'pengguna'])->name('pengguna');
    Route::patch('/pengguna/{user}/peran', [AdminController::class, 'updateRole'])->name('pengguna.peran');
    Route::get('/laporan', [AdminController::class, 'laporan'])->name('laporan');
    Route::patch('/laporan/{report}', [AdminController::class, 'updateLaporan'])->name('laporan.update');
    Route::get('/verifikasi', [AdminController::class, 'verifikasi'])->name('verifikasi');
    Route::delete('/verifikasi/{user}', [AdminController::class, 'resetWajah'])->name('verifikasi.reset');
    Route::get('/profil', [ProfileController::class, 'show'])->name('profil');
});
