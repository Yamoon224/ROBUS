<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\IntegrationClient;

class AuthenticateApiIntegrateClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $apiKey = $request->header('X-API-Key');
        $apiSecret = $request->header('X-API-Secret');

        if (!$apiKey || !$apiSecret) {
            return response()->json(['message' => 'API Key / Secret manquants'], 401);
        }

        $client = IntegrationClient::with('sidUser')
            ->where('api_key', $apiKey)
            ->where('api_secret', $apiSecret)
            ->first();

        if (!$client) {
            return response()->json(['message' => 'Unauthorized API Client'], 401);
        }

        // Optionnel : authentifier l'utilisateur associé
        Auth::login($client->sidUser);

        return $next($request);
    }
}
