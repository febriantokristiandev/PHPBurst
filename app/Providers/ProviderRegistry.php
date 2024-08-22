<?php

namespace App\Providers;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Providers\TwigProvider;
use App\Providers\SessionProvider;

class ProviderRegistry
{
    public static function register(ContainerBuilder $container)
    {
        // Register TwigProvider
        TwigProvider::register($container);

        // Register SessionProvider
        SessionProvider::register($container);

        // Add more providers if needed
    }
}
