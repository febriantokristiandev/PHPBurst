<?php

use FastRoute\RouteCollector;

return function (RouteCollector $r) {
    $r->get('/api/data', 'App\Http\Controllers\ApiController::getData');
};
