<?php

namespace App\Http\Middleware;

class SessionInit
{
    public function handle($request, $next)
    {
        // Get the session object
        $session = $request->session();
        
        if (!$session->has('_csrf_token')) {
            $csrfToken = bin2hex(random_bytes(32));
            $session->set('_csrf_token', $csrfToken);
        }

        

        // Proceed to the next middleware or request handler
        $response = $next($request);

        return $response;
    }
}
