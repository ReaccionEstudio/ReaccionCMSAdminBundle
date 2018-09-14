<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use Symfony\Component\HttpFoundation\Request;


	class PageListController extends Controller
	{
		public function index(Request $request)
		{
			$em = $this->getDoctrine()->getManager();
			$pages = $em->getRepository(Page::class)->findAll();

			// pagination
			$paginator = $this->get('knp_paginator');
		    $pages = $paginator->paginate(
		        $pages,
		        $request->query->get("page"),
		        $this->getParameter("pagination_page_limit")
		    );

			return $this->render("@ReaccionCMSAdminBundle/pages/list.html.twig",
				[
					'pages' => $pages
				]
			);
		}
	}