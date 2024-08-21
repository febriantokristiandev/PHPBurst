<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;
use Workerman\Worker;
use App\Console\Kernel;
use App\Providers\ProviderRegistry;
use Symfony\Component\DependencyInjection\ContainerBuilder;

$container = new ContainerBuilder();
$GLOBALS['container'] = $container;

require __DIR__ . '/../config/global-functions.php';

ProviderRegistry::register($container);

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

$config = require __DIR__ . '/../config/database.php';
$capsule = new Capsule;
$capsule->addConnection($config['connections'][$config['default']]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$port = env('APP_PORT') ?? 8080;
$worker = new Worker("http://0.0.0.0:$port");

$kernel = new Kernel();

$worker->onMessage = function ($connection, $request) use ($kernel) {
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
        'body' => '',
        'cookies' => []
    ];

    $response = $kernel->handle($serverRequest, $response, function ($request, $response) {
        return $response;
    });

    $responseBody = $response['body'];
    $contentType = $response['headers']['Content-Type'] ?? 'text/html';

    $responseString = "HTTP/1.1 200 OK\r\n";
    $responseString .= "Content-Type: {$contentType}\r\n";

    foreach ($response['headers'] as $name => $value) {
        $responseString .= "{$name}: {$value}\r\n";
    }

    if (!empty($response['cookies'])) {
        foreach ($response['cookies'] as $cookieName => $cookieAttributes) {
            $cookieHeader = "Set-Cookie: {$cookieName}={$cookieAttributes['value']}; Path=/; HttpOnly";
            if (isset($cookieAttributes['expires'])) {
                $cookieHeader .= "; Expires=" . gmdate('D, d M Y H:i:s T', $cookieAttributes['expires']);
            }
            if (isset($cookieAttributes['max-age'])) {
                $cookieHeader .= "; Max-Age=" . $cookieAttributes['max-age'];
            }
            $responseString .= "{$cookieHeader}\r\n";
        }
    }

    $responseString .= "Content-Length: " . strlen($responseBody) . "\r\n";
    $responseString .= "\r\n{$responseBody}";

    $connection->send($responseString);
};

Worker::runAll();
