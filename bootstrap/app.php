<?php
require __DIR__ . '/../vendor/autoload.php';

//Workerman 
use Workerman\Worker;
use Workerman\Protocols\Http\Session;
use Workerman\Protocols\Http\Session\FileSessionHandler;
use Workerman\Protocols\Http\Session\RedisSessionHandler;
use Workerman\Connection\TcpConnection;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;
//Additional
use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\DependencyInjection\ContainerBuilder;
//Internal
use App\Console\Kernel;
use App\Providers\ProviderRegistry;
use Flareon\Support\Facades\Facade;

//Init Symfony Container
$container = new ContainerBuilder();
$GLOBALS['container'] = $container;

//Load globa funcs
require __DIR__ . '/../config/global-functions.php';

//Init Dotenv
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

//Config DB
$config = require base_path('config/database.php');
$capsule = new Capsule;
$capsule->addConnection($config['connections'][$config['default']]);
$capsule->setAsGlobal();
$capsule->bootEloquent();

//Obj Init
$port = env('APP_PORT') ?? 8080;
$worker = new Worker("http://0.0.0.0:$port");
$kernel = new Kernel();

// Configure session handling based on the configuration
$sessConfig = require base_path('config/session.php');
$sessionDriver = $sessConfig['driver'];
if ($sessionDriver === 'file') {
    FileSessionHandler::sessionSavePath($sessConfig['file']['path']);
} elseif ($sessionDriver === 'redis') {
    $redisConfig = $sessConfig['redis'];
    Session::handlerClass(RedisSessionHandler::class, $redisConfig);
}

//Load Provider and Facades
ProviderRegistry::register($container);
Facade::setContainer($container);

$worker->onWorkerStart = function(Worker $worker)
{
    echo "Worker starting...\n";
};

$worker->onConnect = function(TcpConnection $connection)
{
    echo "new connection from ip " . $connection->getRemoteIp() . "\n";
};

//Main func
$worker->onMessage = function (TcpConnection $connection, Request $request) use ($kernel) {
    try {
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
    } catch (\Throwable $e) {
        $logger = $GLOBALS['container']->get('logger');

        $logger->error($e);

        $response = new Response(500, ['Content-Type' => 'text/html'], $e);
        $connection->send($response);
    }
};

$worker->onBufferDrain = function(TcpConnection $connection)
{
    echo "buffer drain and continue send\n";
};

$worker->onBufferFull = function(TcpConnection $connection)
{
    echo "bufferFull and do not send again\n";
};

$worker->onError = function(TcpConnection $connection, $code, $msg)
{
    echo "error $code $msg\n";
};

$worker->onClose = function(TcpConnection $connection)
{
    echo "connection closed\n";
};

Worker::runAll();
