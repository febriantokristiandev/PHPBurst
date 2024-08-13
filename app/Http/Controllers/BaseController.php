<?php

namespace App\Http\Controllers;

use Illuminate\Database\Capsule\Manager as Capsule;
use App\Helpers\CsrfResponseHelper;

class BaseController
{
    protected $res;
    protected $db;

    public function __construct()
    {
        $this->res = new CsrfResponseHelper();
        $this->db = Capsule::connection();
    }
}
