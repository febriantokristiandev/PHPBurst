<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;
use Workerman\Worker;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Console\Kernel;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

// Load database configuration
$config = require __DIR__ . '/../config/database.php';

$capsule = new Capsule;
$capsule->addConnection($config['connections'][$config['default']]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Setup Twig template engine
$twig = new Environment(new FilesystemLoader(__DIR__ . '/../resources/views'));

// Initialize Workerman worker
$port = $_ENV['APP_PORT'] ?? 8080;
$worker = new Worker("http://0.0.0.0:$port");

$kernel = new Kernel();

$worker->onMessage = function ($connection, $request) use ($twig, $kernel) {
    
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

    $response = $kernel->handle($serverRequest, $response, function ($request, $response) use ($twig) {
        return $response;
    });

    $responseBody = $response['body'];
    $contentType = $response['headers']['Content-Type'] ?? 'text/html';

    $responseString = "HTTP/1.1 200 OK\r\n";
    foreach ($response['headers'] as $name => $value) {
        $responseString .= "{$name}: {$value}\r\n";
    }
    $responseString .= "Content-Length: " . strlen($responseBody) . "\r\n";
    $responseString .= "\r\n{$responseBody}";

    $connection->send($responseString);
};

Worker::runAll();
