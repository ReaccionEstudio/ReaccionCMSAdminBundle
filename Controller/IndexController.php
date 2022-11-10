<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{
    public function index()
    {
        $widgetStats = $this->get("reaccion_cms_admin.dashboard")->getWidgetsStats();

        return $this->render("@ReaccionCMSAdminBundle/dashboard/dashboard.html.twig",
            [
                'widgetStats' => $widgetStats
            ]
        );
    }
}
