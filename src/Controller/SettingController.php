<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SettingController extends AbstractController
{
    #[Route('/settings', name: 'app_settings')]
    public function settingsPage(): Response
    {
        return $this->render(
            'dashboard/settings/settings.html.twig',
        );
    }
}