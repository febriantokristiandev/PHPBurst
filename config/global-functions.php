<?php

use Laminas\Session\Container as LaminasContainer;
use App\Extensions\Twig\CsrfExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;

function base_path($path = '')
{
    return dirname(__DIR__, 1) . '/' . $path;
}

function view($template, $data = [])
{

    //SymfoniDependencyInjection Init
    global $container;
    /** @var ContainerInterface $container */
    $template = $template . '.twig';
    $twig = $container->get('twig');

    //Laminas Container Init
    $session = new LaminasContainer('csrf');


    $csrfToken = $session->offsetExists('_csrf_token') ? $session->offsetGet('_csrf_token') : null;

    if (!$twig->hasExtension(CsrfExtension::class)) {
        $twig->addExtension(new CsrfExtension($csrfToken));
    }

    $html = $twig->render($template, array_merge($data, ['_csrf_token' => $csrfToken]));

    return [
        'headers' => ['Content-Type' => 'text/html'],
        'body' => $html,
    ];
}
