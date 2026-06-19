<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redis;
use Laravel\Passport\Client;

class PassportController extends Controller
{
    public function redirect(Request $request){
        $clientId = $request->client_id;
        $client = Client::findOrFail($clientId);
        if(! $client){
            throw new InvalidArgumentException('Invalid client ID');
        }
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

        throw_unless($state && $codeVerifier && $state === $request->state, InvalidArgumentException::class);

        $response = Http::post(config('app.url') . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => $clientId,
            'redirect_uri' => config('passport.redirect_uri'),
            'code_verifier' => $codeVerifier,
            'code' => $request->code,
        ]);

        $request->session()->put('access_token', $response->json()['access_token']);
        $request->session()->put('refresh_token', $response->json()['refresh_token']);
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
        return redirect(config('app.frontend_url') . '/auth/success');

    }
}
