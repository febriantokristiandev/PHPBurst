<?php

namespace App\Console;

use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;
use App\Http\Pipeline\Pipeline;

class Kernel
{
    protected $dispatcher;
    protected $middlewareGroups;

    public function __construct()
    {
        // Initialize route dispatchers for web and API routes
        $this->dispatcher = [
            'web' => $this->createDispatcher(__DIR__ . '/../../routes/web.php'),
            'api' => $this->createDispatcher(__DIR__ . '/../../routes/api.php'),
        ];

        // Define middleware groups for different route types
        $this->middlewareGroups = [
            'web' => [
                \App\Http\Middleware\SessionInit::class,
                \App\Http\Middleware\VerifyCsrfMiddleware::class
            ],
            'api' => [],
        ];
    }

    public function handle($request, $next)
    {
        // Determine route type (web or api) based on URI
        $routeType = $this->getRouteType($request->uri());
        $routeInfo = $this->dispatcher[$routeType]->dispatch($request->method(), $request->uri());
    
        // Handle route based on the dispatcher result
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                return $this->handleNotFound();
            case Dispatcher::METHOD_NOT_ALLOWED:
                return $this->handleMethodNotAllowed();
            case Dispatcher::FOUND:
                return $this->handleFound($routeInfo, $request, $routeType);
            default:
                return $this->handleNotFound();
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

    protected function handleNotFound()
    {
        return $this->createResponse('404 Not Found', 404, 'text/html');
    }

    protected function handleMethodNotAllowed()
    {
        return $this->createResponse('405 Method Not Allowed', 405, 'text/plain');
    }

    protected function handleFound($routeInfo, $request, $routeType)
    {
        
        [$class, $method] = explode('::', $routeInfo[1]);

        if (!class_exists($class) || !method_exists($class, $method)) {
            return $this->handleError();
        }

        $controller = new $class();
        $middleware = $this->getMiddlewareForRoute($routeType);

        $pipeline = new Pipeline($middleware);

        return $pipeline->handle($request, function ($req) use ($controller, $method) {
            return $controller->$method($req);
        });
    }

    protected function handleError()
    {
        return $this->createResponse('500 Internal Server Error', 500, 'text/plain');
    }

    protected function createResponse($body, $status,$contentType)
    {

        $response = response();
        $headers = ['Content-Type' => $contentType];

        $response->custom($body,$status,$headers);

        return $response;
    }
}
