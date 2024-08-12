<?php

namespace App\Helpers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Extensions\Twig\CsrfExtension;

class TwigView
{
    protected $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../resources/views');
        $this->twig = new Environment($loader);
        $csrfToken = $this->generateCsrfToken(); 
        $this->twig->addExtension(new CsrfExtension($csrfToken));
    }

    public function render($view, $data = [])
    {
        return $this->twig->render($view, $data);
    }

    private function generateCsrfToken()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf_token'];
    }
}
