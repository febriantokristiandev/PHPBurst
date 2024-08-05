<?php

namespace App\Performance;

use Predis\Client;

class Cache
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'scheme' => 'tcp',
            'host'   => getenv('REDIS_HOST'),
            'port'   => getenv('REDIS_PORT'),
        ]);
    }

    public function set($key, $value, $ttl = 3600)
    {
        $this->client->setex($key, $ttl, $value);
    }

    public function get($key)
    {
        return $this->client->get($key);
    }
}
