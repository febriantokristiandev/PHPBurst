<?php
namespace App\Http\Middleware;

class CsrfMiddleware
{
    public function __construct()
    {
        $this->generateCsrfToken();
    }

    
    public function handle($request, $response, $next)
    {
        if (in_array($request['method'], ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $token = $request['body']['_csrf_token'] ?? '';

            if (!hash_equals($_SESSION['_csrf_token'] ?? '', $token)) {
                error_log("Invalid CSRF token");
                http_response_code(403);
                echo "Invalid CSRF token";
                exit;
            }
        }

        return $next($request, $response);
    }

    private function generateCsrfToken()
    {
        if (empty($_SESSION['_csrf_token'])) {
            $token = bin2hex(random_bytes(32));
            $_SESSION['_csrf_token'] = $token;
            
        }
    }
}
