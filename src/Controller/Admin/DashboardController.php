<?php

namespace App\Controller\Admin;

use App\Entity\Count;
use App\Entity\Defect;
use App\Entity\Reason;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

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
            ->setTitle('DEFECT MANAGEMENT PROJECT');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Counts', 'fas fa-list', Count::class);
        yield MenuItem::linkToCrud('Reasons', 'fas fa-list', Reason::class);
        yield MenuItem::linkToCrud('Defects', 'fas fa-list', Defect::class);
    }
}
