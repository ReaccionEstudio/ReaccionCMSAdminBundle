<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Configuration;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration;

	class ConfigurationListController extends Controller
	{
		public function index(Request $request)
		{
			$em = $this->getDoctrine()->getManager();
			$config = $em->getRepository(Configuration::class)->findBy(
						array(),
						array('id' => 'DESC')
					 );

			// pagination
			$paginator = $this->get('knp_paginator');
		    $config = $paginator->paginate(
		        $config,
		        $request->query->getInt('page', 1),
		        $this->getParameter("pagination_page_limit")
		    );

			return $this->render("@ReaccionCMSAdminBundle/configuration/list.html.twig",
				[
					'config' => $config
				]
			);
		}
	}