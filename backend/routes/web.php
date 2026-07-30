<?php

use App\Events\TestBroadcastEvent;
use App\Http\Controllers\Auth\AuthSessionController;
use App\Http\Controllers\Auth\PassportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    // dd(app('router')->getMiddleware());
    return view('welcome');
});
// Auth Routes
Route::get('/auth/redirect', [PassportController::class, 'redirect'])->name('passport.redirect');
Route::get('/auth/callback', [PassportController::class, 'callback'])->name('passport.callback');

Route::get('/register', [RegisterUserController::class, 'create'])->name('register');
Route::post('/register', [RegisterUserController::class, 'store']);

Route::get('/login', [AuthSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthSessionController::class, 'store']);
Route::post('/logout', [AuthSessionController::class, 'destroy'])->name('logout');

Route::get('/session-test', function (Illuminate\Http\Request $request) {
    return [
        'session_id' => $request->session()->getId(),
        'session_name' => $request->session()->getName(),
        'session' => $request->session()->all(),
        'authenticated' => Auth::check(),
        'user' => Auth::user(),
    ];
});

Route::get('/test-queue', function () {
    event(new TestBroadcastEvent('Hello Horizon'));

    return 'Dispatched';
});

// routes/web.php

// Route::middleware([
//     'oauth.session',
//     'auth:api',
// ])->get('/user', function () {
//     return 'OK';
// });
// Route::prefix('api')
//     ->middleware([
//         'oauth.session',
//         // 'auth:api',
//     ])
//     ->group(function () {

//         Route::get('/user', function (Request $request) {
//             dd([
//                 'bearer' => $request->bearerToken(),
//                 'user' => Auth::guard('api')->user(),
//             ]);
//             return $request->user();
//         });

//     });
