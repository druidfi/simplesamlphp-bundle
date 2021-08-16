<?php

namespace Druidfi\SimpleSamlPhpBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LoginController extends AbstractController
{
    /**
     * Login.
     */
    public function login(): RedirectResponse
    {
        return $this->redirectToRoute('index');
    }

    /**
     * Logout.
     */
    public function logout()
    {
    }
}
