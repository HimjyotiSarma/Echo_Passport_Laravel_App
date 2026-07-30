<?php

use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// Route::middleware([
//     'oauth.session',
//     'auth:api'
// ])->group(function(){
//     Route::get('/user', function (Request $request) {
//         // return json_encode($request);
//         print_r("There is a incoming request");
//         // dd(session()->all());
//         return $request->user();
//     });
// });
Route::middleware([
    'auth:api',
])->group(function(){
    Route::get('/user', function (Request $request) {
        return $request->user()->toResource();
    });
});


// Route::get('/debug', function (Request $request) {
//     return [
//         'authorization' => $request->header('Authorization'),
//         'server' => $_SERVER,
//     ];
// });
