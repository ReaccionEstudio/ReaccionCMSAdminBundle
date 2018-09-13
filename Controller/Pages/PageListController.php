<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;

	class PageListController extends Controller
	{
		public function index()
		{
			$em = $this->getDoctrine()->getManager();
			$pages = $em->getRepository(Page::class)->findAll();

			return $this->render("@ReaccionCMSAdminBundle/pages/list.html.twig",
				[
					'pages' => $pages
				]
			);
		}
	}