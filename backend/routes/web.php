<?php

use App\Http\Controllers\Auth\PassportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Auth Routes
Route::get('auth/redirect', [PassportController::class, 'redirect']);
Route::get('auth/callback', [PassportController::class, 'callback']);
