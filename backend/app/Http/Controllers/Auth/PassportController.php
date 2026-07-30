<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Laravel\Passport\Client;

class PassportController extends Controller
{
    public function redirect(Request $request){
        // var_dump("CURRENT CLIENT", $request->all());
        if(! Auth::check()){
            $request->session()->put('oauth.pending_request', [
                'client_id' => $request->client_id,
                'scope' => $request->scope
            ]);
            return redirect()->route('login');
        };
        $clientId = $request->client_id;
        $client = Client::findOrFail($clientId);
        // if(! $client){
        //     throw new InvalidArgumentException('Invalid client ID');
        // }
        $request->session()->put('client_id', $clientId);
        // $redirectUri = $request->redirect_uri;
        // Create a random state string and store it in the session
        $request->session()->put('state', $state = Str::random(40));
        // Create a Code Verifier and store it in the session
        $request->session()->put('code_verifier', $codeVerifier = Str::random(128));

        $codeChallenge = strtr(rtrim(base64_encode(hash('sha256', $codeVerifier, true)), '='), '+/', '-_');

        $query = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => config('passport.redirect_uri'),
            'response_type' => 'code',
            'scope' => $request->scope,
            'state' => $state,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
        ]);
        return redirect(config('app.url') . '/oauth/authorize?'. $query);
    }
    public function callback(Request $request){
        $state = $request->session()->pull('state');
        $codeVerifier = $request->session()->pull('code_verifier');
        $clientId = $request->session()->pull('client_id');

        // var_dump([
        //     'state' => $state,
        //     'codeVerifier' => $codeVerifier,
        //     'clint_Id' => $clientId,
        //     'request_state' => $request->state
        // ]);
        // dd([
        //     'session_state' => $state,
        //     'request_state' => $request->state,
        //     'code_verifier' => $codeVerifier,
        //     'client_id' => $clientId,
        // ]);
        // dump([
        //     'session_state' => $state,
        //     'request_state' => $request->state,
        //     'code_verifier' => $codeVerifier,
        //     'client_id' => $clientId,
        // ]);

        throw_unless($state && $codeVerifier && $state === $request->state, InvalidArgumentException::class);

        // dump("This is running okay");
        // Dispatch the token request internally.
        // This avoids making an HTTP request back into the same
        // application and works in both development and production.
        $tokenRequest = Request::create('/oauth/token', 'POST', [
            'grant_type' => 'authorization_code',
            'client_id' => $clientId,
            'redirect_uri' => config('passport.redirect_uri'),
            'code_verifier' => $codeVerifier,
            'code' => $request->code,
        ]);

        $response = app()->handle($tokenRequest);

        $data = json_decode($response->getContent(), true);

        if($response->getStatusCode() !== 200){
            throw new InvalidArgumentException($data['error_descrption'] ?? 'Unable to exchange authorization code.');
        }
        // This Kind of request is not possible with dev server
        // (as it cannot handle multiple request in a single request instance)
        // (But possible in production as the php server is handled with nginx / php-fpm)
        // $response = Http::post('/oauth/token', [
        //     'grant_type' => 'authorization_code',
        //     'client_id' => $clientId,
        //     'redirect_uri' => config('passport.redirect_uri'),
        //     'code_verifier' => $codeVerifier,
        //     'code' => $request->code,
        // ]);


        // $request->session()->put('access_token', $response->json()['access_token']);
        // $request->session()->put('refresh_token', $response->json()['refresh_token']);
        // $request->session()->put('access_token', $data['access_token']);
        // $request->session()->put('refresh_token', $data['refresh_token']);
        // $request->session()->put('oauth', [
        //     'client_id' => $clientId,
        //     'access_token' => $data['access_token'],
        //     'refresh_token' => $data['refresh_token'],
        //     'expires_at' => now()->addSeconds($data['expires_in'])
        // ]);
        // The Session Id is only for demonstration purposes, in a real application you would not return it to the client
        // Also remove the data key from the response, as it
        // contains the access token and refresh token which should not be returned to the
        // client(The data key is only for demonstration purposes, in a real application you
        // would not return it to the client)
        // return [
        //     'message' => 'Authentication successful',
        //     'session_id' => $request->session()->getId(),
        //     'data' => $response->json()
        // ];
        // dd($data['access_token']);
        return redirect(config('app.frontend_url') . '/api/auth/finalize')->cookie(cookie(
            'access_token',
            $data['access_token'],
            (int) ceil($data['expires_in']),
            '/',
            null,
            false, // true if HTTPS
            true, // httpOnly = false so Next.js can read it
            false,
            'Lax'
        ));
    }
}
