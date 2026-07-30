<?php
namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class ApiExceptionRenderer{
    public static function unauthenticated(AuthenticationException $e, Request $request){
        return response()->json([
            'success' => false,
            'status' => 401,
            "message" =>  "Authentication failed.",
            'errors' => [
                'auth' => [
                    'Your access token is invalid, expired, or missing.'
                ]
            ]
        ], 401);
    }
}
