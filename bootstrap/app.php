<?php

require __DIR__ . '/../vendor/autoload.php';

use Workerman\Worker;
use Workerman\Protocols\Http\Session;
use Workerman\Protocols\Http\Session\FileSessionHandler;
use Workerman\Protocols\Http\Session\RedisSessionHandler;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;

use Dotenv\Dotenv;

use Illuminate\Database\Capsule\Manager as Capsule;

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

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

$config = require base_path('config/database.php');
$capsule = new Capsule;
$capsule->addConnection($config['connections'][$config['default']]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$port = env('APP_PORT') ?? 8080;

$worker = new Worker("http://0.0.0.0:$port");
$kernel = new Kernel();

// Configure session handling based on the configuration
$config = require base_path('config/session.php');
$sessionDriver = $config['driver'];
if ($sessionDriver === 'file') {
    FileSessionHandler::sessionSavePath($config['file']['path']);
} elseif ($sessionDriver === 'redis') {
    $redisConfig = $config['redis'];
    Session::handlerClass(RedisSessionHandler::class, $redisConfig);
}

$worker->onMessage = function (TcpConnection $connection, Request $request) use ($kernel) {
    $uri = $request->uri();
    $publicPath = __DIR__ . '/../public';
    $filePath = $publicPath . $uri;

    static $staticExtensions = [
        '.css', '.js', '.png', '.jpg', '.jpeg', '.gif', '.ico', '.svg','.woff', '.woff2', '.ttf', '.eot','.pdf', '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx',
        '.webp', '.bmp', '.tiff', '.tif', '.mp4', '.webm', '.ogg', '.mp3','.zip', '.tar', '.gz', '.bz2','.json', '.xml', '.yaml', '.yml'
    ];

    $extension = strtolower(pathinfo($uri, PATHINFO_EXTENSION));
    $extension = $extension ? '.' . $extension : '';

    if (in_array($extension, $staticExtensions)) {
        if (file_exists($filePath)) {
            $fileContent = file_get_contents($filePath);
            $mimeType = mime_content_type($filePath);

            $response = new Response(
                200,
                ['Content-Type' => $mimeType, 'Content-Length' => strlen($fileContent)],
                $fileContent
            );
            $connection->send($response);
            return;
        } else {
            $response = new Response(404, [], 'File not found');
            $connection->send($response);
            return;
        }
    }
    
    $kernelResponse = $kernel->handle($request, function ($request, $response) {
        return $response;
    });

    $connection->send($kernelResponse);
};

Worker::runAll();
