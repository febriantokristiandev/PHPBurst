<?php

namespace Flareon\Support\Facades;

use Symfony\Component\DependencyInjection\ContainerBuilder;

abstract class Facade
{
    protected static $container;

    public static function setContainer(ContainerBuilder $container)
    {
        static::$container = $container;
    }

    protected static function getFacadeAccessor()
    {
        throw new \Exception('Facade accessor method not implemented.');
    }

    public static function __callStatic($method, $arguments)
    {
        if (static::$container === null) {
            throw new \Exception('Container not set.');
        }

        $instance = static::$container->get(static::getFacadeAccessor());
        if ($instance === null) {
            throw new \Exception('No instance found for ' . static::getFacadeAccessor());
        }

        return $instance->$method(...$arguments);
    }
}
