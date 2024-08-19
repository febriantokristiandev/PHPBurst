<?php

namespace App\Http\Controllers;

use App\Models\User;

class HomeController extends BaseController
{
    public function getDataQueryBuilder() {
        return $this->db->table('users')->get();
    }

    public function getDataORM() {
        return User::all();
    }

    public function submit($req, $res)
    {
        return $this->json([
            'success' => true,
            'data' => $this->getDataORM()
        ]);
    }

    public function index($req, $res)  
    {
        return view('home', [
            'name' => 'PHPBurst'
        ]);
    }

    public function form($request, $response)
    {
        return view('form');
    }
}
