<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;

class HomeController
{
    protected $responseHelper;

    public function __construct()
    {
        $this->responseHelper = new ResponseHelper();
    }

    public function submit($request, $response)
    {
        return $this->responseHelper->json([
            'success' => true,
            'data' => 123
        ]);
    }

    public function index($request, $response)
    {
        return $this->responseHelper->view('home.twig', [
            'name' => 'PHPBurst'
        ]);
    }

    public function form($request, $response)
    {
        return $this->responseHelper->view('form.twig');
    }
}
