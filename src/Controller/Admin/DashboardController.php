<?php

namespace App\Controller\Admin;

use App\Controller\HomeController;
use App\Entity\Resources\File;
use App\Entity\Resources\Note;
use App\Entity\Resources\Project;
use App\Entity\User;
use Dom\Entity;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    ) {
    }

    public function index(): Response
    {
        // Standaard doorverwijzen naar de gebruikers
        $url = $this->adminUrlGenerator->setController(UserCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Beheerpaneel')
            ->setFaviconPath('favicon.ico')
            ->setTranslationDomain('admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        // Correcte koppelingen met juiste entiteiten
        yield MenuItem::linkToCrud('Gebruikers', 'fa fa-users', User::class);
        yield MenuItem::linkToCrud('Projecten', 'fa fa-folder', Project::class);
        yield MenuItem::linkToCrud('Bestanden', 'fa fa-file', File::class);
        yield MenuItem::linkToCrud('Notities', 'fa fa-sticky-note', Note::class);

        // Link naar frontend
        yield MenuItem::linkToRoute('Naar website', 'fa fa-external-link', 'home');
    }
}
