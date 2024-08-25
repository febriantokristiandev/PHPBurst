<?php
use Flareon\Handlers\Http\ResponseHelper;

function response($request = null)
{
    return new ResponseHelper($request);
}
