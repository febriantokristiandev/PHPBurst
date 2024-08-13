<?php

namespace App\Http\Middleware;

class StartSessionMiddleware
{
    public function handle($request, $response, $next)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        return $next($request, $response);
    }
}
