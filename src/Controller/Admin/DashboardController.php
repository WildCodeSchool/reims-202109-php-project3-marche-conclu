<?php

namespace App\Controller\Admin;

use App\Entity\Slot;
use App\Entity\Space;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;

// ...

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin")
     */
    public function index(): Response
    {
        // redirect to some CRUD controller
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            // you can include HTML contents too (e.g. to link to an image)
            ->setTitle('<img width=80%; src="build/images/logoMarcheConclu.e7719d0c.png">');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Panneau central', 'fa fa-home');
        yield MenuItem::linkToCrud('Espaces', 'fas fa-list', Space::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Réservations', 'fas fa-calendar', Slot::class);
    }
    // ...
}
