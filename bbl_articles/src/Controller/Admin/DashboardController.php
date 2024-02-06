<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Adherent;
use App\Entity\Auteur;
use App\Entity\Categorie;
use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Entity\Reservations;
use App\Entity\Utilisateur;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Bbl Articles');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Catalogue');
        yield MenuItem::linkToCrud('Auteur', 'fa fa-file-text', Auteur::class);
        yield MenuItem::linkToCrud('Categorie', 'fa fa-folder', Categorie::class);
        yield MenuItem::linkToCrud('Emprunt', 'fa fa-folder', Emprunt::class);
        yield MenuItem::linkToCrud('Livre', 'fa fa-folder', Livre::class);
        yield MenuItem::linkToCrud('Reservations', 'fa fa-folder', Reservations::class);
        yield MenuItem::section('Configuration');
        yield MenuItem::linkToCrud('Utilisateur', 'fa fa-folder', Utilisateur::class);
    }
}
