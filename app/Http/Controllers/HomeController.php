<?php

namespace App\Http\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class HomeController
{
    protected $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../../resources/views');
        $this->twig = new Environment($loader);
    }

    public function index($request, $response)
    {
        $html = $this->twig->render('home.twig', [
            'name' => 'PHPBurst'
        ]);

        // Menyesuaikan respons untuk Workerman
        $response['headers']['Content-Type'] = 'text/html';
        $response['body'] = $html;
        
        return $response;
    }
}
