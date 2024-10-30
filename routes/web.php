<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisDokumenController;
use App\Http\Controllers\KriteriaPenilaianController;

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

//Auth routes
Route::get('/', [AuthController::class, 'loginView'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('auth-login');
Route::get('/register', [AuthController::class, 'registerView'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('auth-register');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth-logout');

//Dashboard routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

//User routes
Route::get('/users', [UserController::class, 'index'])->name('user.index');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('user.destroy');

//Jenis Dokumen routes
Route::get('/jenis-dokumen', [JenisDokumenController::class, 'index'])->name('jenis-dokumen.index');
Route::post('/jenis-dokumen', [JenisDokumenController::class, 'store'])->name('jenis-dokumen.store');
Route::put('/jenis-dokumen/{id}', [JenisDokumenController::class, 'update'])->name('jenis-dokumen.update');
Route::delete('/jenis-dokumen/{id}', [JenisDokumenController::class, 'destroy'])->name('jenis-dokumen.destroy');

//Kriteria Penilaian routes
Route::get('/kriteria-penilaian/{jenis_dokumen_id}', [KriteriaPenilaianController::class, 'index'])->name('kriteria-penilaian.index');
Route::post('/kriteria-penilaian', [KriteriaPenilaianController::class, 'store'])->name('kriteria-penilaian.store');
Route::put('/kriteria-penilaian/{id}', [KriteriaPenilaianController::class, 'update'])->name('kriteria-penilaian.update');
Route::delete('/kriteria-penilaian/{id}', [KriteriaPenilaianController::class, 'destroy'])->name('kriteria-penilaian.destroy');
