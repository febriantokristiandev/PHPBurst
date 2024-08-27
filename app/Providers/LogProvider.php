<?php

namespace App\Providers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class LogProvider
{
    public static function register(ContainerBuilder $container)
    {
        // Load configuration from logging.php
        $config = require base_path('config/logging.php');

        // Get log path and level from configuration
        $logPath = $config['channels']['single']['path'] ?? __DIR__ . '/../../storage/logs/monolog.log';
        $logLevel = $config['channels']['single']['level'] ?? 'debug';

        // Map the log level string to Monolog\Level enum
        $level = self::mapLogLevel($logLevel);

        // Register the Logger service
        $container->register('logger', Logger::class)
            ->setFactory([self::class, 'createLogger'])
            ->addMethodCall('pushHandler', [new StreamHandler($logPath, $level)]);
    }

    public static function createLogger()
    {
        return new Logger('application');
    }

    private static function mapLogLevel(string $level): Level
    {
        return match (strtolower($level)) {
            'debug' => Level::Debug,
            'info' => Level::Info,
            'notice' => Level::Notice,
            'warning' => Level::Warning,
            'error' => Level::Error,
            'critical' => Level::Critical,
            'alert' => Level::Alert,
            'emergency' => Level::Emergency,
            default => Level::Debug, // Default level
        };
    }
}
