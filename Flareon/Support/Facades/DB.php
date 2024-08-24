<?php

namespace Flareon\Support\Facades;

class DB extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'db'; 
    }
}
