<?php

use Laminas\Session\Container;
use App\Extensions\Twig\CsrfExtension;

function base_path($path = '') {
    return dirname(__DIR__, 1) . '/' . $path;
}

function view($template, $data = [])
{
    global $container;

    $template = $template.'.twig';

    $session = new Container('csrf');
    $csrfToken = $session->offsetExists('_csrf_token') ? $session->offsetGet('_csrf_token') : null;

    $twig = $container->get('twig');

    if (!$twig->hasExtension(CsrfExtension::class)) {
        $twig->addExtension(new CsrfExtension($csrfToken));
    }
    
    $html = $twig->render($template, array_merge($data, ['_csrf_token' => $csrfToken]));

    return [
        'headers' => ['Content-Type' => 'text/html'],
        'body' => $html,
    ];
}

