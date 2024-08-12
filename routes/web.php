<?php

use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    // $r->get('/', 'App\Http\Controllers\HomeController::index');
    $r->get('/home', 'App\Http\Controllers\HomeController::index');
    $r->get('/form', 'App\Http\Controllers\HomeController::form');
    $r->post('/submit', 'App\Http\Controllers\HomeController::submit');
};