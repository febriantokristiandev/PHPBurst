<?php

namespace App\Http\Pipeline;

class Pipeline
{
    protected $middleware = [];

    public function __construct(array $middleware = [])
    {
        $this->middleware = $middleware;
    }

    public function handle($request, $response, $finalDestination)
    {
        $pipeline = array_reduce(
            array_reverse($this->middleware),
            function ($stack, $middleware) {
                return function ($request, $response) use ($stack, $middleware) {
                    return (new $middleware)->handle($request, $response, $stack);
                };
            },
            $finalDestination
        );
        return $pipeline($request, $response);
    }
}
