<?php
namespace App\Providers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigProvider
{
    public static function register(ContainerBuilder $container)
    {
        $loader = new FilesystemLoader(base_path('resources/views'));

        $container->register('twig.loader', FilesystemLoader::class)
            ->setArguments([base_path('resources/views')]);

        $container->register('twig', Environment::class)
            ->setArguments([
                $loader,
                [
                    'cache' => base_path('cache/twig'), // Set direktori cache khusus
                    'debug' => true,                   // Aktifkan debug (opsional)
                ]
            ]);
    }
}


