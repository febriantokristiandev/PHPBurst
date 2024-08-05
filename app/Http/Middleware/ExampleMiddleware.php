<?php

namespace App\Http\Middleware;

class ExampleMiddleware
{
    public function process($request, $response, $next)
    {
        // Add middleware logic here
        
        // Call the next middleware or handler
        return $next($request, $response);
    }
}
