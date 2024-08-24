<?php

function base_path($path = '')
{
    return dirname(__DIR__, 1) . '/' . $path;
}

