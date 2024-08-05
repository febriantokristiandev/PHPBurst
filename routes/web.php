<?php

use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    // $r->get('/', 'App\Http\Controllers\HomeController::index');
    $r->get('/home', 'App\Http\Controllers\HomeController::index');
};