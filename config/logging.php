<?php

return [
    'default' => env('LOG_CHANNEL', 'stack'),

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => __DIR__ . '/../storage/logs/monolog.log',
            'level' => 'debug',
        ],

        // Example configuration for other channels (commented out):
        /*
        'database' => [
            'driver' => 'custom', // Example custom driver
            'handler' => \Monolog\Handler\SomeDatabaseHandler::class,
            'level' => 'error',
            'connection' => [
                'host' => env('DB_HOST', 'localhost'),
                'database' => env('DB_DATABASE', 'logs'),
                'username' => env('DB_USERNAME', 'root'),
                'password' => env('DB_PASSWORD', ''),
            ],
        ],
        */
    ],
];
