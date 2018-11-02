<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use Symfony\Component\HttpFoundation\Request;


	class PageDetailController extends Controller
	{
		public function index(Page $page)
		{
			return $this->render("@ReaccionCMSAdminBundle/pages/detail.html.twig",
				[
					'page' => $page
				]
			);
		}
	}