<?php

return [
    // Session name
    'session_name' => 'my_session',

    // Session cookie settings
    'cookie_params' => [
        'lifetime' => 3600,        // Duration of the session cookie in seconds
        'path' => '/',             // Path for the cookie
        'domain' => '',            // Domain for the cookie
        'secure' => false,        // If the cookie should only be sent over HTTPS
        'httponly' => true,       // If the cookie should only be accessible by HTTP and not by JavaScript
        'samesite' => 'Lax',      // SameSite attribute for the cookie
    ],

    // Session handler configuration
    'session_handler' => [
        // Default session driver
        'type' => 'file',         // Default session driver: 'file', 'memcached', 'redis', etc.

        // Only used if 'type' is 'file'
        'path' => base_path('storage/sessions'), // Path to store session files
    ],

    // Configuration for Memcached driver
    /*
    'session_handler' => [
        'type' => 'memcached',   // Change to 'memcached' to use Memcached
        'servers' => [
            [
                'host' => 'localhost', // Memcached server host
                'port' => 11211,        // Memcached server port
            ],
        ],
    ],
    */

    // Configuration for Redis driver
    /*
    'session_handler' => [
        'type' => 'redis',       // Change to 'redis' to use Redis
        'connection' => [
            'host' => 'localhost', // Redis server host
            'port' => 6379,        // Redis server port
            'password' => null,   // Redis password if any
            'database' => 0,      // Redis database number
        ],
    ],
    */

    // Configuration for Database driver
    /*
    'session_handler' => [
        'type' => 'database',    // Change to 'database' to use Database
        'connection' => 'mysql', // Database connection name (defined in config/database.php)
        'table' => 'sessions',   // Table name to store sessions
    ],
    */

    // Configuration for Cookie driver
    /*
    'session_handler' => [
        'type' => 'cookie',      // Change to 'cookie' to use Cookie
        'cookie' => [
            'name' => 'my_session_cookie', // Cookie name
            'lifetime' => 3600,            // Duration of the cookie in seconds
            'path' => '/',                 // Path for the cookie
            'domain' => '',                // Domain for the cookie
            'secure' => false,            // If the cookie should only be sent over HTTPS
            'httponly' => true,           // If the cookie should only be accessible by HTTP
            'samesite' => 'Lax',          // SameSite attribute for the cookie
        ],
    ],
    */

    // Configuration for Array driver
    /*
    'session_handler' => [
        'type' => 'array',      // Change to 'array' to use Array
        // Array driver does not require additional configuration
    ],
    */

    // Configuration for Null driver
    /*
    'session_handler' => [
        'type' => 'null',       // Change to 'null' to use Null
        // Null driver does not require additional configuration
    ],
    */
];
