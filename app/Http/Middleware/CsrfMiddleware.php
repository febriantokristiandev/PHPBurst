<?php

namespace App\Http\Middleware;

use Laminas\Session\Container;

class CsrfMiddleware
{
    public function handle($request, $response, $next)
    {
        $session = new Container('csrf');

        if (!$session->offsetExists('_csrf_token')) {
            $this->generateCsrfToken($session);
        }

        if (in_array($request['method'], ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $token = $request['body']['_csrf_token'] ?? '';

            if (!hash_equals($session->offsetGet('_csrf_token'), $token)) {
                http_response_code(403);
                echo "Invalid CSRF token";
                exit;
            }
        }

        return $next($request, $response);
    }

    private function generateCsrfToken($session)
    {
        $token = bin2hex(random_bytes(32));
        $session->offsetSet('_csrf_token', $token);
    }
}
