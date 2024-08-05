<?php

namespace App\Exceptions;

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

class ErrorHandler
{
    public static function register()
    {
        $whoops = new Run;
        $whoops->pushHandler(new PrettyPageHandler);
        $whoops->register();
    }
}
