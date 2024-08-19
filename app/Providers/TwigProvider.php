<?php

namespace App\Providers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TwigProvider
{
    public static function register(ContainerBuilder $container)
    {
        $container->register('twig.loader', FilesystemLoader::class)
            ->setArguments([base_path('resources/views')]);

        $container->register('twig', Environment::class)
            ->setArguments([new Reference('twig.loader')]);
    }
}
