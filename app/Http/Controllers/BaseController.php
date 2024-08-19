<?php

namespace App\Http\Controllers;

use Illuminate\Database\Capsule\Manager as Capsule;

class BaseController
{
    protected $db;

    public function json($data)
    {
        return [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode($data),
        ];
    }

    public function __construct()
    {
        $this->db = Capsule::connection();
    }
}
