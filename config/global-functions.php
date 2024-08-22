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
    global $container;
    /** @var ContainerInterface $container */
    
    $template = $template . '.twig';
    $twig = $container->get('twig');
    $session = $container->get('session');

    $csrfToken = $session->getSegment('Vendor\Package\ClassName')->get('csrf_token', null);

    // Add CSRF extension to Twig if not already added
    if (!$twig->hasExtension(CsrfExtension::class)) {
        $twig->addExtension(new CsrfExtension($csrfToken));
    }

    // Render the template with CSRF token
    $html = $twig->render($template, array_merge($data, ['_csrf_token' => $csrfToken]));

    return [
        'headers' => ['Content-Type' => 'text/html'],
        'body' => $html,
    ];
}
