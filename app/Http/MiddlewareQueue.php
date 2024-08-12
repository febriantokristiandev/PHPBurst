<?php

namespace App\Http;

use App\Http\Pipeline\Pipeline;

class MiddlewareQueue
{
    protected $pipeline;

    public function __construct(array $middleware = [])
    {
        $this->pipeline = new Pipeline($middleware);
    }

    public function handle($request, $response, $next)
    {
        $staticFileExtensions = ['.ico', '.png', '.jpg', '.css', '.js'];

        $uri = $request['uri'];
        foreach ($staticFileExtensions as $extension) {
            if (strpos($uri, $extension) !== false) {
                return $next($request, $response);
            }
        }

        return $this->pipeline->handle($request, $response, $next);
    }
}
