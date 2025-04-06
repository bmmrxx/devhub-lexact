<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FileController extends AbstractController
{
    #[Route('/file', name: 'file')]
    public function filePage(): Response
    {
        return $this->render(
            'dashboard/file/index.html.twig',
        );
    }
}