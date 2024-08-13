<?php

namespace App\Helpers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Extensions\Twig\CsrfExtension;

class CsrfResponseHelper
{
    protected $twig;
    protected $csrfToken;

    public function json($data)
    {
        return [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($data),
        ];
    }

    public function view($view, $data = [])
    {
        $this->csrfToken = $this->getCsrf();
        $loader = new FilesystemLoader(__DIR__ . '/../../resources/views');
        $this->twig = new Environment($loader);
        $this->twig->addExtension(new CsrfExtension($this->csrfToken));

        $html = $this->twig->render($view, array_merge($data, ['_csrf_token' => $this->csrfToken]));
        
        return [
            'headers' => ['Content-Type' => 'text/html'],
            'body' => $html,
        ];
    }

    private function getCsrf()
    {
        if (!empty($_SESSION['_csrf_token'])) {
            return $_SESSION['_csrf_token'];
        }

        return 'Csrf not set';
    }
}
