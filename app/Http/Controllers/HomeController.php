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

    public function submit($request, $response)
    {
        return $this->res->json([
            'success' => true,
            'data' => $this->getDataORM()
        ]);
    }

    public function index($request, $response)  
    {
        return $this->res->view('home.twig', [
            'name' => 'PHPBurst'
        ]);
    }

    public function form($request, $response)
    {
        return $this->res->view('form.twig');
    }
}
