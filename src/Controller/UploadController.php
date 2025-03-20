<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UploadController extends AbstractController
{
    #[Route('/upload', name: 'upload')]
    public function uploadPage(): Response
    {
        return $this->render(
            'dashboard/uploads/upload.html.twig',
        );
    }
}