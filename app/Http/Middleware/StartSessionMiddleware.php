<?php

namespace App\Http\Middleware;

class StartSessionMiddleware
{
    public function handle($request, $response, $next)
    {
        global $container;
        
        $session = $container->get('session');

        $segment = $session->getSegment('Vendor\Package\ClassName');

        if (!$segment->get('csrf_token')) {
            $csrfToken = bin2hex(random_bytes(32));
            $segment->set('csrf_token', $csrfToken);
        }

        $request['csrf_token'] = $segment->get('csrf_token');

        return $next($request, $response);
    }
}
