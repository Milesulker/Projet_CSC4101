<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Librairie;
use App\Entity\Livre;
use App\Entity\Member;
use App\Entity\Galerie;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(LivreCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('DNDBooks Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoCrud('Les Livres', 'fas fa-list', Livre::class);
        yield MenuItem::linkToCrud('Les Librairies', 'fas fa-list', Librairie::class);
        yield MenuItem::linkToCrud('Les Membres', 'fas fa-list', Member::class);
        yield MenuItem::linkToCrud('Les Galeries', 'fas fa-list', Galerie::class);
    }
}
