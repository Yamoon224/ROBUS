<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Cache\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class SecureApiMiddleware
{
    protected $limiter;

    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next)
    {
        // Seulement pour les routes d'intégration
        if ($request->is('api/v1/integration/*')) {
            return $this->handleIntegration($request, $next);
        }
        
        // Pour les autres routes API, passer directement
        return $next($request);
    }
    
    protected function handleIntegration(Request $request, Closure $next)
    {
        // 1. Rate Limiting spécifique à l'intégration
        $key = 'integration:' . $request->ip();
        $maxAttempts = 100; // 100 requêtes par minute
        $decayMinutes = 1;

        if ($this->limiter->tooManyAttempts($key, $maxAttempts)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests'
            ], 429);
        }

        $this->limiter->hit($key, $decayMinutes * 60);

        // 2. Vérification Content-Type pour les méthodes modifiantes
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE']) && !$request->isJson()) {
            $this->limiter->clear($key);
            return response()->json([
                'success' => false,
                'message' => 'Content-Type must be application/json'
            ], 415);
        }

        // 3. Headers requis
        $apiKey = $request->header('X-API-Key');
        $apiSecret = $request->header('X-API-Secret');
        
        if (!$apiKey || !$apiSecret) {
            return response()->json([
                'success' => false,
                'message' => 'API credentials required'
            ], 401);
        }

        // 4. Protection rejeu (optionnel mais recommandé)
        $timestamp = $request->header('X-Request-Timestamp');
        if ($timestamp && abs(time() - (int)$timestamp) > 300) {
            return response()->json([
                'success' => false,
                'message' => 'Request timestamp expired'
            ], 400);
        }

        // 5. Log sécurisé (ne pas logger les secrets complets)
        Log::channel('api')->info('Integration API Request', [
            'method' => $request->method(),
            'path' => $request->path(),
            'ip' => $request->ip(),
            'api_key_prefix' => substr($apiKey, 0, 8) . '...'
        ]);

        $response = $next($request);

        // 6. Headers de sécurité
        return $response->header('X-Content-Type-Options', 'nosniff')
                       ->header('X-Frame-Options', 'DENY')
                       ->header('X-XSS-Protection', '1; mode=block');
    }
}