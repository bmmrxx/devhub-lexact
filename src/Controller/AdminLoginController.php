<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminLoginController extends AbstractController
{
    #[Route('/admin-login', name: 'admin-login')]
    public function adminLoginPage(): Response
    {
        return $this->render(
            'login/adminLogin.html.twig',
        );
    }
}