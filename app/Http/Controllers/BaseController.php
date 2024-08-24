<?php

namespace App\Http\Controllers;

use Illuminate\Database\Capsule\Manager as Capsule;


class BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Capsule::connection();
    }
}
