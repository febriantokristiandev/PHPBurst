<?php
namespace App\Support\Session;

use Laminas\Session\Container as LaminasContainer;
use Laminas\Session\SessionManager;

class SessionInitializer
{
    private $session;
    private $sessionPath;

    public function __construct()
    {
        $this->sessionPath = base_path('storage/sessions');
        $this->initialize();
    }

    public function initialize()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_save_path($this->sessionPath);

            $sessionConfig = new \Laminas\Session\Config\SessionConfig();
            $sessionConfig->setOptions([
                'save_path' => $this->sessionPath,
                'name'      => 'my_session',
                'gc_maxlifetime' => 1440,
                'gc_probability' => 1,
                'gc_divisor' => 100,
            ]);

            $this->session = new SessionManager($sessionConfig);
            $this->session->start();
        }

        $csrfContainer = new LaminasContainer('csrf', $this->session);
        if (!$csrfContainer->offsetExists('_csrf_token')) {
            $token = bin2hex(random_bytes(32));
            $csrfContainer->offsetSet('_csrf_token', $token);
        }
    }

    public function getSession()
    {
        return $this->session;
    }
}
