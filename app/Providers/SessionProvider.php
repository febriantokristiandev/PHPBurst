<?php

namespace App\Providers;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use App\Handler\SessionHandlers\FileSessionHandler;
use App\Handler\SessionHandlers\ArraySessionHandler;
use App\Handler\SessionHandlers\CookieSessionHandler;
use App\Handler\SessionHandlers\DatabaseSessionHandler;
use App\Handler\SessionHandlers\NullSessionHandler;
use App\Handler\SessionHandlers\MemcachedSessionHandler;
use App\Handler\SessionHandlers\RedisSessionHandler;
use Aura\Session\SessionFactory;
use Aura\Session\Session;

class SessionProvider
{
    public static function register(ContainerBuilder $container)
    {
        // Register the Aura Session factory
        $container->register('session.factory', SessionFactory::class);

        // Define the session service with a factory method
        $definition = new Definition();
        $definition->setFactory([self::class, 'createSession']);
        $definition->setPublic(true);

        $container->setDefinition('session', $definition);
    }

    public static function createSession()
    {
        $sessionFactory = new SessionFactory();
        $config = require base_path('config/session.php'); // Load configuration
        
        $sessionType = $config['session_handler']['type'];
        $sessionHandler = self::getSessionHandler($sessionType, $config['session_handler']);

        // Create session instance
        $session = $sessionFactory->newInstance($_COOKIE);
        $session->setName($config['session_name']);
        
        session_set_cookie_params(
            $config['cookie_params']['lifetime'],
            $config['cookie_params']['path'],
            $config['cookie_params']['domain'],
            $config['cookie_params']['secure'],
            $config['cookie_params']['httponly']
        );

        return $session;
    }

    private static function getSessionHandler(string $type, array $config)
    {
        switch ($type) {
            case 'file':
                return new FileSessionHandler($config['path']);
            case 'array':
                return new ArraySessionHandler();
            case 'cookie':
                return new CookieSessionHandler($config['cookie']);
            case 'database':
                return new DatabaseSessionHandler($config['connection'], $config['table']);
            case 'null':
                return new NullSessionHandler();
            case 'memcached':
                return new MemcachedSessionHandler($config);
            case 'redis':
                return new RedisSessionHandler($config);
            default:
                throw new \InvalidArgumentException("Unsupported session handler type: $type");
        }
    }
}
