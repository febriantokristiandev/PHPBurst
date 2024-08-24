<?php

namespace App\Http\Middleware;

class VerifyCsrfMiddleware
{
    public function handle($request, $next)
    {
        if (in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $body = $request->post();
            $token = $body['_csrf_token'] ?? '';

            $session = $request->session();

            $csrfToken = $session->get('_csrf_token', '');

            if (!hash_equals($csrfToken, $token)) {
                // Send a 403 Forbidden response if the tokens do not match
                return response()->custom('Invalid CSRF token', 403);
            }
        }

        return $next($request);
    }
}
