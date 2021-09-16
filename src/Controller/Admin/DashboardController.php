<?php

namespace App\Controller\Admin;

use App\Entity\Calendar;
use App\Entity\Schedule;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        // return parent::index();
        // redirect to some CRUD controller
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(ScheduleCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('IntraCalendar');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Hasiera', 'fa fa-home');
        yield MenuItem::linkToCrud('Egutegiak', 'fas fa-calendar', Calendar::class);
        yield MenuItem::linkToCrud('Ekitaldiak', 'fas fa-clock', Schedule::class);
    }


}
