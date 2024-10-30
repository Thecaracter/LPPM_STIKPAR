<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;

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
Route::get('/user', [UserController::class, 'index'])->name('user.index');
Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');