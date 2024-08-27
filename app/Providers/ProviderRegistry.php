<?php

namespace App\Providers;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\Providers\TwigProvider;
use App\Providers\DbProvider;

class ProviderRegistry
{
    public static function register(ContainerBuilder $container)
    {
        TwigProvider::register($container);
        DbProvider::register($container);
        LogProvider::register($container);

        // Add more providers if needed
    }
}
