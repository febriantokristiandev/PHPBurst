<?php

namespace Flareon\Handlers\Http;

use Extensions\Twig\CsrfExtension;
use Workerman\Protocols\Http\Response;
use Flareon\Support\Facades\TwigFacade;

class ResponseHelper
{
    protected $status = 200;
    protected $headers = [];
    protected $body = '';
    protected $cookies = [];

    protected $request;

    public function __construct($req)
    {
        $this->request = $req;
    }

    public function view($viewName, $data = [])
    {
        $template = $viewName . '.twig';
        
        $html = TwigFacade::render($template, $data);

        if (!$this->request) {
            return new Response(200, ['Content-Type' => 'text/html'], $html);
        }

        if (!method_exists($this->request, 'session') || !$this->request->session()) {
            return new Response(200, ['Content-Type' => 'text/html'], $html);
        }

        //Session available

        $session = $this->request->session();
        $csrfToken = $session->has('_csrf_token') ? $session->get('_csrf_token', '') : null;

        if ($csrfToken !== null) {
            if (!TwigFacade::hasExtension(CsrfExtension::class)) {
                TwigFacade::addExtension(new CsrfExtension($csrfToken));
            }
            $html = TwigFacade::render($template, array_merge($data, ['_csrf_token' => $csrfToken]));
            $response = new Response(200, ['Content-Type' => 'text/html'], $html);
            $response->cookie('XSRF-TOKEN', $csrfToken);
            return $response;
        }
        
        return new Response(200, ['Content-Type' => 'text/html'], $html);
    }


    // JSON Response
    public function json($data, $status = 200)
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode($data));
    }

    // Redirect Response
    public function redirect($url, $status = 302)
    {
        return new Response($status, ['Location' => $url]);
    }

    // Response with JSONP
    public function jsonp($callback, $data)
    {
        $jsonData = json_encode($data);
        $content = "{$callback}({$jsonData});";
        return new Response(200, ['Content-Type' => 'application/javascript'], $content);
    }

    // Custom Response
    public function custom($content, $status = 200, array $headers = [])
    {
        return new Response($status, $headers, $content);
    }

}
