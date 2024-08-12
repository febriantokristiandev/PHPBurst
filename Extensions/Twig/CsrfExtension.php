<?php

namespace App\Extensions\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CsrfExtension extends AbstractExtension
{
    private $csrfToken;

    public function __construct($csrfToken)
    {
        $this->csrfToken = htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8');
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('csrf', [$this, 'getCsrfToken'], ['is_safe' => ['html']])
        ];
    }

    public function getCsrfToken()
    {
        return "<input type='hidden' name='_csrf_token' value='{$this->csrfToken}'>";
    }
}
