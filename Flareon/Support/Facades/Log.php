<?php

namespace Flareon\Support\Facades;

class Log extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'logger'; 
    }
}
