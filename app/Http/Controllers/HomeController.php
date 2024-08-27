<?php

namespace App\Http\Controllers;

use App\Models\User;
use Flareon\Support\Facades\DB;
use Flareon\Support\Facades\Log;

class HomeController 
{
    public function getDataQueryBuilder() {
        return DB::table('users')->get();
    }

    public function getDataORM() {
        return User::all();
    }

    public function submit($req)
    {
        return response()->json([ 
            'success' => true,
            'data' => $this->getDataQueryBuilder()
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
