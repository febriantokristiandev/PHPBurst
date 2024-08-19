<?php

namespace App\Http\Middleware;

use Laminas\Session\SessionManager;
use Laminas\Session\Storage\SessionArrayStorage;

class StartSessionMiddleware
{
    private $session;

    public function handle($request, $response, $next)
    {
        if ($this->session && $this->session->isStarted()) {
            $request['session'] = $this->session;
            return $next($request, $response);
        }

        $this->session = new SessionManager();
        $this->session->setStorage(new SessionArrayStorage());
        $this->session->start();

        $request['session'] = $this->session;

        $response = $next($request, $response);
        $this->session->writeClose();

        return $response;
    }
}
