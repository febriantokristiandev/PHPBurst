<?php

namespace App\Handler\SessionHandlers;

use SessionHandlerInterface;

class CookieSessionHandler implements SessionHandlerInterface
{
    protected $cookieParams;

    public function __construct(array $cookieParams)
    {
        $this->cookieParams = $cookieParams;
    }

    public function open($savePath, $sessionName): bool
    {
        return true; // Opening a cookie session is not required
    }

    public function close(): bool
    {
        return true; // Closing a cookie session is not required
    }

    public function read($id): string
    {
        $cookieName = $this->cookieParams['name'] ?? 'PHPSESSID';
        return $_COOKIE[$cookieName] ?? '';
    }

    public function write($id, $data): bool
    {
        $cookieName = $this->cookieParams['name'] ?? 'PHPSESSID';
        $lifetime = $this->cookieParams['lifetime'] ?? 3600;
        $path = $this->cookieParams['path'] ?? '/';
        $domain = $this->cookieParams['domain'] ?? '';
        $secure = $this->cookieParams['secure'] ?? false;
        $httponly = $this->cookieParams['httponly'] ?? true;
        $samesite = $this->cookieParams['samesite'] ?? 'Lax';

        setcookie(
            $cookieName,
            $data,
            [
                'expires' => time() + $lifetime,
                'path' => $path,
                'domain' => $domain,
                'secure' => $secure,
                'httponly' => $httponly,
                'samesite' => $samesite
            ]
        );

        return true;
    }

    public function destroy($id): bool
    {
        $cookieName = $this->cookieParams['name'] ?? 'PHPSESSID';

        setcookie(
            $cookieName,
            '',
            [
                'expires' => time() - 3600,
                'path' => $this->cookieParams['path'] ?? '/',
                'domain' => $this->cookieParams['domain'] ?? '',
                'secure' => $this->cookieParams['secure'] ?? false,
                'httponly' => $this->cookieParams['httponly'] ?? true,
                'samesite' => $this->cookieParams['samesite'] ?? 'Lax'
            ]
        );

        return true;
    }

    public function gc(int $maxlifetime): int|false
    {
        return 0; // No actual garbage collection needed for cookies
    }
}
