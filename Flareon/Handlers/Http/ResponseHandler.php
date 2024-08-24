<?php

namespace Flareon\Handlers\Http;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Extensions\Twig\CsrfExtension;
use Workerman\Protocols\Http\Response;
use Flareon\Support\Facades\TwigFacade;

class ResponseHandler
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

    // Simulated View Response
    public function view($viewName, $data = [])
    {

        $errorSessionUndefined = "Please provide request param in the response() function, e.g., response(request).";
        $tokenNotFound = "token undefined";

        $session = $this->request->session();

        global $container;
        /** @var ContainerInterface $container */
        
        $template = $viewName . '.twig';

        if (!$session->has('_csrf_token')) {
            $csrfToken = $tokenNotFound;
        } else {
            $csrfToken = $session->get('_csrf_token', '');
        }

        if (!TwigFacade::hasExtension(CsrfExtension::class)) {
            TwigFacade::addExtension(new CsrfExtension($csrfToken));
        }

        $html = TwigFacade::render($template, array_merge($data, ['_csrf_token' => $csrfToken]));

        
        return new Response(200, ['Content-Type' => 'text/html'], $html);
    }

    // JSON Response
    public function json($data, $status = 200)
    {
        return new Response($status, ['Content-Type' => 'application/json'], json_encode($data));
    }

    // String Response
    public function string($content, $status = 200)
    {
        return new Response($status, ['Content-Type' => 'text/plain'], $content);
    }

    // Redirect Response
    public function redirect($url, $status = 302)
    {
        return new Response($status, ['Location' => $url]);
    }

    // File Response
    public function file($filePath)
    {
        if (!file_exists($filePath)) {
            return new Response(404, [], 'File not found');
        }
        $headers = [
            'Content-Type' => mime_content_type($filePath),
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
            'Content-Length' => filesize($filePath)
        ];
        return (new Response())->withFile($filePath)->withHeaders($headers);
    }

    // Stream Response
    public function stream(callable $callback, $status = 200)
    {
        return new Response($status, ['Content-Type' => 'text/plain'], $callback);
    }

    // Custom Response
    public function custom($content, $status = 200, array $headers = [])
    {
        return new Response($status, $headers, $content);
    }

    // HTTP Status Code Responses
    public function status($status)
    {
        return new Response($status, $this->headers, $this->body);
    }

    // Response Macros (mock implementation)
    public function macro($name, callable $callback)
    {
        // This method is a placeholder for Laravel's response macros
        return $callback($this);
    }

    // HTTP Cache Responses
    public function cache($maxAge = 3600)
    {
        $this->headers['Cache-Control'] = "max-age={$maxAge}";
        return new Response($this->status, $this->headers, $this->body);
    }

    // Custom Response Classes
    public function customClass($className, $params = [])
    {
        if (class_exists($className)) {
            $reflection = new \ReflectionClass($className);
            return $reflection->newInstanceArgs($params);
        }
        return null;
    }

    // Flash Messages (mock implementation)
    public function flash($message)
    {
        // Mock flash message handling (Laravel-specific)
        $_SESSION['flash_message'] = $message;
        return $this->string($message);
    }

    // Conditional Responses
    public function conditional($condition, $trueResponse, $falseResponse)
    {
        return $condition ? $trueResponse : $falseResponse;
    }

    // Response with Headers
    public function withHeaders(array $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
        return new Response($this->status, $this->headers, $this->body);
    }

    // Response with Cookies
    public function withCookies(array $cookies)
    {
        foreach ($cookies as $name => $value) {
            $this->cookies[] = "$name=$value";
        }
        $this->headers['Set-Cookie'] = implode('; ', $this->cookies);
        return new Response($this->status, $this->headers, $this->body);
    }

    // Response with JSONP
    public function jsonp($callback, $data)
    {
        $jsonData = json_encode($data);
        $content = "{$callback}({$jsonData});";
        return new Response(200, ['Content-Type' => 'application/javascript'], $content);
    }

    // Download Streamed File
    public function downloadStream($filePath)
    {
        if (!file_exists($filePath)) {
            return new Response(404, [], 'File not found');
        }
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . basename($filePath) . '"',
            'Content-Length' => filesize($filePath)
        ];
        return new Response(200, $headers, function () use ($filePath) {
            readfile($filePath);
        });
    }


}
