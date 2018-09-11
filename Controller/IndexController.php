<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller;

	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;

	class IndexController extends Controller
	{
		public function index()
		{
			return $this->render("@ReaccionCMSAdminBundle/dashboard/dashboard.html.twig");
		}
	}