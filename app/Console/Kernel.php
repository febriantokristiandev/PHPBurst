<?php

namespace App\Console;

use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use FastRoute\Dispatcher;
use App\Http\Pipeline\Pipeline;

class Kernel
{
    protected $dispatcher;
    protected $middlewareGroups;
    protected $container; 

    public function __construct()
    {

        $this->dispatcher = [
            'web' => $this->createDispatcher(__DIR__ . '/../../routes/web.php'),
            'api' => $this->createDispatcher(__DIR__ . '/../../routes/api.php'),
        ];

        $this->middlewareGroups = [
            'web' => [
                \App\Http\Middleware\StartSessionMiddleware::class,
                \App\Http\Middleware\CsrfMiddleware::class
            ],
            'api' => [],
        ];
    }

    public function handle($request, $response, $next)
    {

        $routeType = $this->getRouteType($request['uri']);
        $routeInfo = $this->dispatcher[$routeType]->dispatch($request['method'], $request['uri']);

        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                return $this->handleNotFound($response);
            case Dispatcher::METHOD_NOT_ALLOWED:
                return $this->handleMethodNotAllowed($response);
            case Dispatcher::FOUND:
                return $this->handleFound($routeInfo, $request, $response, $routeType);
            default:
                return $response;
        }
    }

    protected function createDispatcher($routesFile)
    {
        return simpleDispatcher(function (RouteCollector $r) use ($routesFile) {
            $routes = require $routesFile;
            $routes($r);
        });
    }

    protected function getRouteType($uri)
    {
        return str_starts_with($uri, '/api/') ? 'api' : 'web';
    }

    protected function getMiddlewareForRoute($routeType)
    {
        return $this->middlewareGroups[$routeType];
    }

    protected function handleNotFound($response)
    {
        return $this->createResponse($response, '404 Not Found', 'text/html');
    }

    protected function handleMethodNotAllowed($response)
    {
        return $this->createResponse($response, '405 Method Not Allowed', 'text/plain');
    }

    protected function handleFound($routeInfo, $request, $response, $routeType)
    {
        [$class, $method] = explode('::', $routeInfo[1]);

        if (!class_exists($class) || !method_exists($class, $method)) {
            return $this->handleError($response);
        }

        $controller = new $class();
        $middleware = $this->getMiddlewareForRoute($routeType);

        $pipeline = new Pipeline($middleware);
        
        return $pipeline->handle($request, $response, function ($req, $res) use ($controller, $method) {
            return $controller->$method($req, $res);
        });
    }

    protected function handleError($response)
    {
        return $this->createResponse($response, '500 Internal Server Error', 'text/plain');
    }

    protected function createResponse($response, $body, $contentType)
    {
        $response['headers']['Content-Type'] = $contentType;
        $response['body'] = $body;
        return $response;
    }
}
