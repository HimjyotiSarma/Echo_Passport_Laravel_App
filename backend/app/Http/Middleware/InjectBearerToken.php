<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InjectBearerToken
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd("Inject Bearer token is working");
        if(! $request->bearerToken()){
            $token = $request->cookie('access_token');
            if($token){
                $request->headers->set('Authorization', 'Bearer '.$token);
            };
        }
        return $next($request);
    }
}
