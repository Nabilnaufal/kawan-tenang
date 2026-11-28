<?php

use App\Http\Controllers\admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\admin\ReportController as AdminReportController;
use App\Http\Controllers\admin\UserController as AdminUserController;
use App\Http\Controllers\admin\VerificationController as AdminVerificationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\CounselingController;
use App\Http\Controllers\konselor\DashboardController as KonselorDashboardController;
use App\Http\Controllers\konselor\SessionController as KonselorSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\user\BubbleController as UserBubbleController;
use App\Http\Controllers\user\DashboardController as UserDashboardController;
use App\Http\Controllers\user\HomeController as UserHomeController;
use Illuminate\Support\Facades\Route;

// Autentikasi
Route::get('/masuk', [AuthenticatedSessionController::class, 'create'])->name('masuk');
Route::redirect('/login', '/masuk')->name('login');
Route::get('/daftar', [RegisteredUserController::class, 'create'])->name('daftar');
Route::view('/lupa-sandi', 'auth.lupa-sandi')->name('lupa-sandi');
Route::post('/masuk', [AuthenticatedSessionController::class, 'store'])->name('masuk.store');
Route::post('/masuk/wajah', [AuthenticatedSessionController::class, 'faceLogin'])
    ->middleware('throttle:10,1')
    ->name('masuk.wajah');
Route::post('/daftar', [RegisteredUserController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('daftar.store');
Route::post('/keluar', [AuthenticatedSessionController::class, 'destroy'])->name('keluar');
Route::post('/lupa-sandi', fn () => redirect()->route('masuk'))->name('lupa-sandi.store');

// Verifikasi email
Route::middleware('auth')->group(function () {
    Route::get('/email/verifikasi', [EmailVerificationController::class, 'notice'])
        ->name('verification.notice');
    Route::get('/email/verifikasi/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('/email/verifikasi-kirim', [EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
});

// Area pengguna
Route::get('/', [UserHomeController::class, 'index'])->name('home');
Route::get('/bubble', [UserBubbleController::class, 'index'])->name('bubble');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/bubble', [UserBubbleController::class, 'store'])->name('bubble.store');
    Route::delete('/bubble/{post}', [UserBubbleController::class, 'destroy'])->name('bubble.destroy');
    Route::post('/bubble/{post}/komentar', [UserBubbleController::class, 'comment'])->name('bubble.komentar');
    Route::post('/bubble/{post}/suka', [UserBubbleController::class, 'like'])->name('bubble.suka');
    Route::post('/bubble/{post}/lapor', [UserBubbleController::class, 'report'])->name('bubble.lapor');
    Route::view('/verifikasi', 'verifikasi')->name('verifikasi');
    Route::get('/dasbor', [UserDashboardController::class, 'index'])->name('dasbor');
    Route::get('/profil', [ProfileController::class, 'show'])->name('profil');
    Route::patch('/profil', [ProfileController::class, 'update'])->name('profil.update');
    Route::delete('/profil/wajah', [ProfileController::class, 'forgetFace'])->name('profil.wajah.destroy');
});

// Menu konseling bersama pengguna dan konselor
Route::middleware(['auth', 'verified'])->prefix('konseling')->name('konseling.')->group(function () {
    Route::get('/', [CounselingController::class, 'index'])->name('index');
    Route::post('/', [CounselingController::class, 'store'])->name('store');
    Route::get('/{session}/pesan', [CounselingController::class, 'messages'])->name('messages');
    Route::get('/{session}', [CounselingController::class, 'show'])->name('show');
    Route::post('/{session}/pesan', [CounselingController::class, 'message'])->name('message');
    Route::post('/{session}/selesai', [CounselingController::class, 'close'])->name('close');
});

// Area konselor
Route::middleware(['auth', 'verified', 'konselor'])->prefix('konselor')->name('konselor.')->group(function () {
    Route::get('/', [KonselorDashboardController::class, 'index'])->name('dasbor');
    Route::get('/sesi', [KonselorSessionController::class, 'index'])->name('sesi');
    Route::get('/sesi/{session}', [CounselingController::class, 'show'])->name('sesi.show');
    Route::post('/sesi/{session}/catatan', [KonselorSessionController::class, 'note'])->name('sesi.catatan');
    Route::patch('/ketersediaan', [KonselorSessionController::class, 'availability'])->name('ketersediaan');
    Route::get('/profil', [ProfileController::class, 'show'])->name('profil');
});

// Area administrator
Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dasbor');
    Route::get('/pengguna', [AdminUserController::class, 'index'])->name('pengguna');
    Route::patch('/pengguna/{user}/peran', [AdminUserController::class, 'updateRole'])->name('pengguna.peran');
    Route::get('/laporan', [AdminReportController::class, 'index'])->name('laporan');
    Route::patch('/laporan/{report}', [AdminReportController::class, 'update'])->name('laporan.update');
    Route::get('/verifikasi', [AdminVerificationController::class, 'index'])->name('verifikasi');
    Route::delete('/verifikasi/{user}', [AdminVerificationController::class, 'reset'])->name('verifikasi.reset');
    Route::get('/profil', [ProfileController::class, 'show'])->name('profil');
});
