<?php

namespace Flareon\Support\Facades;

class TwigFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'twig';
    }
}