<?php

namespace App\Handler\SessionHandlers;

use SessionHandlerInterface;
use Redis;

class RedisSessionHandler implements SessionHandlerInterface
{
    protected $redis;

    public function __construct(array $config)
    {
        $this->redis = new Redis();
        $this->redis->connect($config['connection']['host'], $config['connection']['port']);
        if (isset($config['connection']['password'])) {
            $this->redis->auth($config['connection']['password']);
        }
        if (isset($config['connection']['database'])) {
            $this->redis->select($config['connection']['database']);
        }
    }

    public function open($savePath, $name): bool
    {
        return true; // Redis does not require any specific actions on open
    }

    public function close(): bool
    {
        return $this->redis->close();
    }

    public function read($id): string
    {
        return $this->redis->get($id) ?: '';
    }

    public function write($id, $data): bool
    {
        return $this->redis->set($id, $data);
    }

    public function destroy($id): bool
    {
        return $this->redis->del($id) > 0;
    }

    public function gc($maxlifetime): int|false
    {
        return true; // Redis handles its own garbage collection
    }
}
