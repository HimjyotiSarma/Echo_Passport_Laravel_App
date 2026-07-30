<?php

namespace App\Services\Auth;

use Illuminate\Http\Request as HttpRequest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OAuthTokenManager
{
    public function getAccessToken(Request $request): ?string{
        $oauth = $request->session()->get('oauth');
        if(! $oauth){
            return null;
        }
        if($this->shouldRefresh($oauth)){
            return $this->refresh($request, $oauth);
        }
        return $oauth['access_token'];

    }
    protected function shouldRefresh(array $oauth): bool{
        return now()->gte($oauth['expires_at']);
    }
    protected function refresh(Request $request, array $oauth): ?string{
        if(empty($oauth['refresh_token'])){
            return null;
        }
        $tokenRequest = HttpRequest::create('/oauth/token', 'POST', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $oauth['refresh_token'],
            'client_id' => $oauth['client_id']
        ]);
        /** @var Response $response*/

        $response = app()->handle($tokenRequest);

        $data = json_decode($response->getContent(), true);

        if(! is_array($data)){
            return null;
        }

        if($response->getStatusCode() !== 200){
            $request->session()->forget('oauth');
            // $request->session()->invalidate();
            // $request->session()->regenerateToken();
            return null;
        }
        $request->session()->put('oauth', [
            'client_id' => $oauth['client_id'],
            'access_token' => $data['access_token'],
            'refresh_token' => $data['refresh_token'],
            'expires_at' => now()->addSeconds($data['expires_in'])
        ]);
        return $data['access_token'];
    }
}
