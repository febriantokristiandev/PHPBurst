<?php

namespace App\Helpers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Extensions\Twig\CsrfExtension;

class ResponseHelper
{
    protected $twig;
    protected $csrfToken;

    public function __construct()
    {
        $this->csrfToken = $this->generateCsrfToken();
        
        $loader = new FilesystemLoader(__DIR__ . '/../../resources/views');
        $this->twig = new Environment($loader);
        $this->twig->addExtension(new CsrfExtension($this->csrfToken));
    }

    public function json($data)
    {
        return [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($data),
        ];
    }

    public function view($view, $data = [])
    {
        $html = $this->twig->render($view, array_merge($data, ['_csrf_token' => $this->csrfToken]));
        return [
            'headers' => ['Content-Type' => 'text/html'],
            'body' => $html,
        ];
    }

    private function generateCsrfToken()
    {
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }
}
