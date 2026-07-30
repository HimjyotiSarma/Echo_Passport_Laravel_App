<?php

namespace App\Http\Middleware;

use App\Services\Auth\OAuthTokenManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OauthSessionMiddleware
{
    public function __construct(protected OAuthTokenManager $tokenManager)
    {
        //   die('constructed');
    }
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // return response()->json([
        //     'message' => "The middleware running okay"
        // ]);

        // dd($request->session());
        // $oauth = $request->session()->get('oauth');
        // if(! $oauth){
        //     return response()->json([
        //         'message' => 'Please Login to access api routes',
        //     ], 401);
        // }
        $token = $this->tokenManager->getAccessToken($request);
        // return response()->json([
        //     'message' => 'The route running Okay'
        // ]);
        if(! $token){
            // Auth::guard('auth:api')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return response()->json([
                'error' => 'Authentication Failed',
                'message' => 'Please Login and try again'
            ], 401);
            // return redirect()->route('passport.redirect');
        }
        $request->headers->set('Authorization', 'Bearer '.$token);
        return $next($request);
    }
}
