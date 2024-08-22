<?php

namespace App\Handler\SessionHandlers;

use SessionHandlerInterface;

class ArraySessionHandler implements SessionHandlerInterface
{
    private $data = [];

    public function open(string $savePath, string $name): bool
    {
        // No resources to open for an array-based session handler
        return true;
    }

    public function close(): bool
    {
        // No resources to close for an array-based session handler
        return true;
    }

    public function read(string $id): string
    {
        return isset($this->data[$id]) ? $this->data[$id] : '';
    }

    public function write(string $id, string $data): bool
    {
        $this->data[$id] = $data;
        return true;
    }

    public function destroy(string $id): bool
    {
        if (isset($this->data[$id])) {
            unset($this->data[$id]);
            return true;
        }
        return false;
    }

    public function gc(int $maxlifetime): int|false
    {
        // No garbage collection needed for an array-based session handler
        return 0;
    }
}
