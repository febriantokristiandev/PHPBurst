<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;
use Workerman\Worker;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Http\MiddlewareQueue;
use App\Http\Middleware\StartSessionMiddleware;
use App\Http\Middleware\CsrfMiddleware;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Register Whoops for error handling
$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

// Initialize database connection
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => $_ENV['DB_CONNECTION'],
    'host'      => $_ENV['DB_HOST'],
    'database'  => $_ENV['DB_DATABASE'],
    'username'  => $_ENV['DB_USERNAME'],
    'password'  => $_ENV['DB_PASSWORD'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
$capsule->bootEloquent();

// Setup FastRoute dispatcher
$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $routes = require __DIR__ . '/../routes/web.php';
    $routes($r);
});

// Setup Twig template engine
$loader = new FilesystemLoader(__DIR__ . '/../resources/views');
$twig = new Environment($loader);

// Initialize Workerman worker
$port = $_ENV['APP_PORT'] ?? 8080;
$worker = new Worker("http://0.0.0.0:$port");

// Middleware Declarations
$middlewareQueue = new MiddlewareQueue([
    StartSessionMiddleware::class,
    CsrfMiddleware::class,
]);

$worker->onMessage = function ($connection, $request) use ($dispatcher, $twig, $middlewareQueue) {
    $_SERVER['REQUEST_METHOD'] = $request->method();

    $serverRequest = [
        'server' => $_SERVER,
        'headers' => $request->header(),
        'query' => $request->get(),
        'body' => $request->post(),
        'cookies' => $request->cookie(),
        'method' => $request->method(),
        'uri' => $request->uri()
    ];

    $response = [
        'headers' => [],
        'body' => ''
    ];        

    $response = $middlewareQueue->handle($serverRequest, $response, function ($request, $response) use ($dispatcher, $twig) {
        
        $routeInfo = $dispatcher->dispatch($request['method'], $request['uri']);
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                $html = $twig->render('404.twig');
                $response['headers']['Content-Type'] = 'text/html';
                $response['body'] = $html;
                break;

            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                $response['headers']['Content-Type'] = 'text/plain';
                $response['body'] = '405 Method Not Allowed';
                break;

            case FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                [$class, $method] = explode('::', $handler);

                if (class_exists($class) && method_exists($class, $method)) {
                    $controller = new $class();
                    $response = $controller->$method($request, $response);
                } else {
                    $response['headers']['Content-Type'] = 'text/plain';
                    $response['body'] = '500 Internal Server Error';
                }
                break;
        }

        return $response;
    });

    $responseBody = $response['body'];
    $contentType = $response['headers']['Content-Type'] ?? 'text/html';

    if (strpos($contentType, 'application/json') !== false) {
        $responseBody = json_encode($responseBody);
        $response['headers']['Content-Length'] = strlen($responseBody);
    } else {
        $response['headers']['Content-Length'] = strlen($responseBody);
    }

    $responseStatus = 200;
    $responseReasonPhrase = 'OK';
    $protocol = $serverRequest['server']['SERVER_PROTOCOL'] ?? 'HTTP/1.1';

    $responseString = "{$protocol} {$responseStatus} {$responseReasonPhrase}\r\n";
    foreach ($response['headers'] as $name => $value) {
        $responseString .= "{$name}: {$value}\r\n";
    }
    $responseString .= "Content-Length: " . strlen($responseBody) . "\r\n";
    $responseString .= "Connection: close\r\n\r\n";
    $responseString .= $responseBody;

    $connection->send($responseString);
};

Worker::runAll();
