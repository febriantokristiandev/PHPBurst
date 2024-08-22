<?php

namespace App\Http\Middleware;

class VerifyCsrfMiddleware
{
    public function handle($request, $response, $next)
    {
        global $container;

        // Handle only POST, PUT, PATCH, DELETE requests
        if (in_array($request['method'], ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $token = $request['body']['_csrf_token'] ?? '';

            $session = $container->get('session');

            $sessionSegment = $session->getSegment('Vendor\Package\ClassName');
            $csrfToken = $sessionSegment->get('csrf_token', '');

            if (!hash_equals($csrfToken, $token)) {
                http_response_code(403);
                echo "Invalid CSRF token";
                exit;
            }
        }

        return $next($request, $response);
    }
}
