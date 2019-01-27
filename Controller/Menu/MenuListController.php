<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Menu;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Services\Menu\MenuService;

	class MenuListController extends Controller
	{
		public function index(Request $request)
		{
			$em = $this->getDoctrine()->getManager();
			$menu = $em->getRepository(Menu::class)->findBy(
						array(),
						array('id' => 'DESC')
					 );

			// pagination
			$paginator = $this->get('knp_paginator');
		    $menu = $paginator->paginate(
				        $menu,
				        $request->query->getInt('page', 1),
				        $this->getParameter("pagination_page_limit")
				    );

			return $this->render("@ReaccionCMSAdminBundle/menu/list.html.twig",
				[
					'menu' => $menu
				]
			);
		}
	}