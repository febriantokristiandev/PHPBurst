<?php
use Flareon\Handlers\Http\ResponseHandler;

function response($request = null)
{
    return new ResponseHandler($request);
}
