<?php

namespace App\Handler\SessionHandlers;

use SessionHandlerInterface;

class NullSessionHandler implements SessionHandlerInterface
{
    public function open(string $savePath, string $sessionName): bool
    {
        return true; // No action required
    }

    public function close(): bool
    {
        return true; // No action required
    }

    public function read(string $id): string
    {
        return ''; // Always return an empty string
    }

    public function write(string $id, string $data): bool
    {
        return true; // Always succeed
    }

    public function destroy(string $id): bool
    {
        return true; // Always succeed
    }

    public function gc(int $maxlifetime): int|false
    {
        return true; // No action required
    }
}
