<?php

namespace Flareon\Support\Facades;

use Twig\Environment;

class TwigFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Environment::class;
    }
}
