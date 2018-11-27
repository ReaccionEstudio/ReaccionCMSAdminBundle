<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;

	class IndexController extends Controller
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