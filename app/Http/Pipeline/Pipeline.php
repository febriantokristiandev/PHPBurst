<?php

namespace App\Http\Pipeline;

class Pipeline
{
    protected $middleware = [];

    public function __construct(array $middleware = [])
    {
        $this->middleware = $middleware;
    }

    /**
     * Handle the request through the pipeline.
     *
     * @param  mixed $request
     * @param  callable $finalDestination
     * @return mixed
     */
    public function handle($request, $response, $finalDestination)
    {
        $stack = array_reduce(
            array_reverse($this->middleware),
            function ($stack, $middleware) {
                return function ($request, $response) use ($stack, $middleware) {
                    $middlewareInstance = new $middleware;
                    return $middlewareInstance->handle($request, $response, $stack);
                };
            },
            $finalDestination
        );
        
        return $stack($request, $response);
    }
}
