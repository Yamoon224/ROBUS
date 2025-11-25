<?php

namespace App\Http\Middleware;

use Closure;

class CheckSuspendedUser
{
    public function handle($request, Closure $next)
    {
        $userAuth = auth_user();

        if ($userAuth && $userAuth->is_suspended) {
            return json_message_response(false, "Votre compte est suspendu, contactez le support client !", 333);
        }

        return $next($request);
    }
}
