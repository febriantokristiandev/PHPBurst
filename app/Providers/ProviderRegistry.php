<?php

namespace App\Providers;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Providers\TwigProvider;

class ProviderRegistry
{
    public static function register(ContainerBuilder $container)
    {
        // Register TwigProvider
        TwigProvider::register($container);

        // Add more providers if needed
    }
}
