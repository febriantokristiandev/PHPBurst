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

    public function submit($req)
    {
        return response()->json([ 
            'success' => true,
            'data' => $this->getDataORM()
        ]);
    }

    public function index($req)  
    {
        return response()->view('home', [
            'name' => 'PHPBurst'
        ]);
    }

    public function form($req)
    {
        
        return response($req)->view('form');
    }
}
