<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AnggotaTimController;
use App\Http\Controllers\JenisDokumenController;
use App\Http\Controllers\CetakPenilaianController;
use App\Http\Controllers\RiwayatDokumenController;
use App\Http\Controllers\KriteriaPenilaianController;

// Auth Routes (Public)
Route::get('/', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth-login');
Route::get('/register', [AuthController::class, 'registerView'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('auth-register');

// Protected Routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth-logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Only Routes
    Route::middleware('role:admin')->group(function () {
        // Users Management
        Route::get('/users', [UserController::class, 'index'])->name('user.index');
        Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::post('/user', [UserController::class, 'store'])->name('user.store');

        // Jenis Dokumen Management
        Route::get('/jenis-dokumen', [JenisDokumenController::class, 'index'])->name('jenis-dokumen.index');
        Route::post('/jenis-dokumen', [JenisDokumenController::class, 'store'])->name('jenis-dokumen.store');
        Route::put('/jenis-dokumen/{id}', [JenisDokumenController::class, 'update'])->name('jenis-dokumen.update');
        Route::delete('/jenis-dokumen/{id}', [JenisDokumenController::class, 'destroy'])->name('jenis-dokumen.destroy');

        // Kriteria Penilaian Management
        Route::get('/kriteria-penilaian/{jenis_dokumen_id}', [KriteriaPenilaianController::class, 'index'])->name('kriteria-penilaian.index');
        Route::post('/kriteria-penilaian', [KriteriaPenilaianController::class, 'store'])->name('kriteria-penilaian.store');
        Route::put('/kriteria-penilaian/{id}', [KriteriaPenilaianController::class, 'update'])->name('kriteria-penilaian.update');
        Route::delete('/kriteria-penilaian/{id}', [KriteriaPenilaianController::class, 'destroy'])->name('kriteria-penilaian.destroy');
    });

    // User Routes (All authenticated users)
    Route::get('/anggota-tim', [AnggotaTimController::class, 'index'])->name('anggota-tim.index');
    Route::post('/anggota-tim', [AnggotaTimController::class, 'store'])->name('anggota-tim.store');
    Route::put('/anggota-tim/{id}', [AnggotaTimController::class, 'update'])->name('anggota-tim.update');
    Route::delete('/anggota-tim/{id}', [AnggotaTimController::class, 'destroy'])->name('anggota-tim.destroy');

    // Dokumen Routes
    Route::get('/dokumen', [DokumenController::class, 'index'])->name('dokumen.index');
    Route::post('/dokumen', [DokumenController::class, 'store'])->name('dokumen.store');
    Route::put('/dokumen/{id}', [DokumenController::class, 'update'])->name('dokumen.update');
    Route::delete('/dokumen/{id}', [DokumenController::class, 'destroy'])->name('dokumen.destroy');

    // Reviewer Routes
    Route::middleware('role:reviewer')->group(function () {
        Route::get('/review', [ReviewController::class, 'index'])->name('review.index');
        Route::get('/review/kriteria/{id}', [ReviewController::class, 'getKriteria']);
        Route::post('/review/{id}', [ReviewController::class, 'update']);
    });

    // Riwayat & Cetak (All authenticated users)
    Route::get('/riwayat', [RiwayatDokumenController::class, 'index'])->name('riwayat.index');
    Route::get('/cetak-penilaian/{id}', [CetakPenilaianController::class, 'show'])->name('cetak-penilaian.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});