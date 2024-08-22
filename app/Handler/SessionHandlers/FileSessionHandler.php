<?php

namespace App\Handler\SessionHandlers;

use SessionHandlerInterface;

class FileSessionHandler implements SessionHandlerInterface
{
    private $path;

    public function __construct($path)
    {
        if (!is_dir($path) && !mkdir($path, 0777, true)) {
            throw new \RuntimeException("Unable to create session directory: $path");
        }
        $this->path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    public function open(string $savePath, string $name): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $id): string
    {
        $file = $this->path . 'sess_' . $id;
        return is_readable($file) ? (string) file_get_contents($file) : '';
    }

    public function write(string $id, string $data): bool
    {
        $file = $this->path . 'sess_' . $id;
        return file_put_contents($file, $data) !== false;
    }

    public function destroy(string $id): bool
    {
        $file = $this->path . 'sess_' . $id;
        return is_writable($file) ? unlink($file) : false;
    }

    public function gc(int $maxlifetime): int|false
    {
        $count = 0;
        foreach (glob($this->path . 'sess_*') as $file) {
            if (filemtime($file) + $maxlifetime < time()) {
                if (@unlink($file)) {
                    $count++;
                }
            }
        }
        return $count;
    }
}
