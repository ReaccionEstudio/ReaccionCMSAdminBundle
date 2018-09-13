<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;

	class PageListController extends Controller
	{
		public function index()
		{
			return $this->render("@ReaccionCMSAdminBundle/pages/list.html.twig");
		}
	}