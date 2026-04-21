<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class SessionTimeout implements FilterInterface
{
    protected $timeout = 1800;

    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        if (!$session->get('isLoggedIn')) {
            return null;
        }

        $lastActivity = $session->get('last_activity');
        
        if ($lastActivity) {
            $elapsed = time() - $lastActivity;
            
            if ($elapsed >= $this->timeout) {
                $session->destroy();
                return redirect()->to('/login')
                    ->with('error', 'Su sesión ha expirado por inactividad. Por favor, inicie sesión nuevamente.');
            }
        }

        $session->set('last_activity', time());

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}