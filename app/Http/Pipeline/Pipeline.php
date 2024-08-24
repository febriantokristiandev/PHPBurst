<?php

namespace App\Http\Pipeline;

class Pipeline
{
    protected $middleware = [];

    public function __construct(array $middleware = [])
    {
        $this->middleware = $middleware;
    }

    public function handle($request, $finalDestination)
    {
        $stack = array_reduce(
            array_reverse($this->middleware),
            function ($stack, $middleware) {
                return function ($request) use ($stack, $middleware) {
                    $middlewareInstance = new $middleware;
                    return $middlewareInstance->handle($request, $stack);
                };
            },
            $finalDestination
        );
        
        return $stack($request);
    }
}
