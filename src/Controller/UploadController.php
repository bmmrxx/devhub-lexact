<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UploadController extends AbstractController
{
    #[Route('/upload', name: 'upload')]
    public function homePage(): Response
    {
        return $this->render(
            'dashboard/home.html.twig',
        );
    }
}