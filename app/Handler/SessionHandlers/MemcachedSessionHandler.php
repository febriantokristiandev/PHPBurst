<?php

namespace App\Handler\SessionHandlers;

use SessionHandlerInterface;
use Memcached;

class MemcachedSessionHandler implements SessionHandlerInterface
{
    protected $memcached;

    public function __construct(array $config)
    {
        $this->memcached = new Memcached();
        foreach ($config['servers'] as $server) {
            $this->memcached->addServer($server['host'], $server['port']);
        }
    }

    public function open($savePath, $name): bool
    {
        return true; // Memcached does not require any specific actions on open
    }

    public function close(): bool
    {
        return true; // Memcached does not require any specific actions on close
    }

    public function read($id): string
    {
        return $this->memcached->get($id) ?: '';
    }

    public function write($id, $data): bool
    {
        return $this->memcached->set($id, $data);
    }

    public function destroy($id): bool
    {
        return $this->memcached->delete($id);
    }

    public function gc($maxlifetime): int|false
    {
        return true; // Memcached does not require garbage collection
    }
}
