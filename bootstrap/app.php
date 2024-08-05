<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use FastRoute\RouteCollector;
use function FastRoute\simpleDispatcher;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;
use Workerman\Worker;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

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

$dispatcher = simpleDispatcher(function (RouteCollector $r) {
    $routes = require __DIR__ . '/../routes/web.php';
    $routes($r);
});

$port = $_ENV['APP_PORT'] ?? 8080;
$worker = new Worker("http://0.0.0.0:$port");

$worker->onMessage = function ($connection, $request) use ($dispatcher) {

    $serverRequest = [
        'server' => $_SERVER,
        'headers' => $request->header(),
        'query' => $request->get(),
        'body' => $request->post(),
        'cookies' => $request->cookie(),
        'method' => $request->method(),
        'uri' => $request->uri()
    ];

    $routeInfo = $dispatcher->dispatch($serverRequest['method'], $serverRequest['uri']);

    $response = [
        'headers' => [],
        'body' => ''
    ];

    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            $response['headers']['Content-Type'] = 'text/plain';
            $response['body'] = '404 Not Found';
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
                $response = $controller->$method($serverRequest, $response);
            } else {
                $response['headers']['Content-Type'] = 'text/plain';
                $response['body'] = '500 Internal Server Error';
            }
            break;
    }

    $responseBody = $response['body'];

    // Check if the response contains HTML
    if (strpos($response['headers']['Content-Type'] ?? '', 'text/html') !== false) {
        // Send only the body for HTML responses
        $connection->send($responseBody);
    } else {
        // Send full HTTP response with headers for other content types
        $headers = '';
        foreach ($response['headers'] as $name => $value) {
            $headers .= "{$name}: {$value}\r\n";
        }

        $responseStatus = 200;
        $responseReasonPhrase = 'OK';
        $protocol = $serverRequest['server']['SERVER_PROTOCOL'] ?? 'HTTP/1.1';

        $responseString = "{$protocol} {$responseStatus} {$responseReasonPhrase}\r\n" .
            $headers .
            "Content-Length: " . strlen($responseBody) . "\r\n" .
            "Connection: close\r\n\r\n" .
            $responseBody;

        $connection->send($responseString);
    }
};

Worker::runAll();
