<?php

namespace App\Providers;

use Illuminate\Database\Capsule\Manager as Capsule;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DbProvider
{
    public static function register(ContainerBuilder $container)
    {
        $container->register('db', Capsule::class)
            ->setFactory([Capsule::class, 'connection']); 
    }
}
