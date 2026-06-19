<?php

use App\Http\Controllers\Auth\AuthSessionController;
use App\Http\Controllers\Auth\PassportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterUserController;

Route::get('/', function () {
    return view('welcome');
});
// Auth Routes
Route::get('/auth/redirect', [PassportController::class, 'redirect']);
Route::get('/auth/callback', [PassportController::class, 'callback']);

Route::get('/register', [RegisterUserController::class, 'create'])->name('register');
Route::post('/register', [RegisterUserController::class, 'store']);

Route::get('/login', [AuthSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthSessionController::class, 'store']);
Route::post('/logout', [AuthSessionController::class, 'destroy'])->name('logout');
