<?php

namespace App\Http\Middleware;

use App\Support\Session\SessionInitializer;

class StartSessionMiddleware
{
    private $sessionInitializer;

    public function __construct()
    {
        $this->sessionInitializer = new SessionInitializer();
    }

    public function handle($request, $response, $next)
    {
        $this->sessionInitializer->initialize();
        $request['session'] = $this->sessionInitializer->getSession();
        return $next($request, $response);
    }
}
