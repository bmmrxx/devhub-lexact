<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SidebarController extends AbstractController
{
    #[Route('/sidebar', name: 'sidebar')]
    public function adminLoginPage(): Response
    {
        return $this->render(
            'dashboard/sidebar.html.twig',
        );
    }
}