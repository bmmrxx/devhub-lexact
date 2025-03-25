<?php

namespace App\Controller;

use App\Enum\UserRoleEnum;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function homePage(): Response
    {
        return $this->render(
            'dashboard/home.html.twig',
        );
    }
}