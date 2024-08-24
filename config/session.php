<?php

return [
    'driver' => 'file',

    'file' => [
        'path' => '../storage/sessions',  // Directory for storing session files
    ],

    // Configuration for Redis storage
    /*
    'redis' => [
        'host'     => '127.0.0.1', // Redis server host
        'port'     => 6379,        // Redis server port
        'timeout'  => 2,           // Connection timeout (optional)
        'auth'     => '******',    // Redis password (optional)
        'database' => 1,           // Redis database number (optional)
        'prefix'   => 'session_',  // Prefix for session keys (optional)
    ],
    */
];
