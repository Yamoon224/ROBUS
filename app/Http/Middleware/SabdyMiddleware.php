<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SabdyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        
        if (Auth::check()) {

            // Vérifie le rôle de l'utilisateur
            if (!sabdyVerify()) {
                // Si la vérification échoue, renvoie une réponse avec le code de statut 403 (Interdit)
                return response()->view('errors.599', [], 599);
            }
        } else {
            // Si l'utilisateur n'est pas connecté, redirige-le vers la page de connexion
            return $next($request);
        }

        return $next($request);
    }
}
