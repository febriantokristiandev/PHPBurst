<?php

namespace App\Http\Middleware;

use Laminas\Session\Container;

class VerifyCsrfMiddleware
{

    public function handle($request, $response, $next)
    {

        if (in_array($request['method'], ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $token = $request['body']['_csrf_token'] ?? '';

            $session = new Container('csrf');
            if (!hash_equals($session->offsetGet('_csrf_token'), $token)) {
                http_response_code(403);
                echo "Invalid CSRF token";
                exit;
            }
        }

        return $next($request, $response);
    }

}
