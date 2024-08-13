<?php

namespace App\Http;

use App\Http\Pipeline\Pipeline;
use App\Http\Middleware\StartSessionMiddleware;
use App\Http\Middleware\CsrfMiddleware;

class MiddlewareQueue
{
    protected $pipeline;

    public function __construct()
    {
        // Inisialisasi pipeline dengan daftar middleware
        $this->pipeline = new Pipeline([
            // StartSessionMiddleware::class,
            CsrfMiddleware::class,
        ]);
    }

    public function handle($request, $response, $next)
    {
        $staticFileExtensions = ['ico', 'png', 'jpg', 'css', 'js'];

        $extension = pathinfo($request['uri'], PATHINFO_EXTENSION);
        if (in_array($extension, $staticFileExtensions)) {
            return $next($request, $response);
        }
        
        return $this->pipeline->handle($request, $response, $next);
    }
}
