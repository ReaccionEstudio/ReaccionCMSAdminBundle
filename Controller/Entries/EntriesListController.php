<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Entries;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;

	class EntriesListController extends Controller
	{
		public function index(Request $request)
		{
			$em = $this->getDoctrine()->getManager();
			$entries = $em->getRepository(Entry::class)->findBy(
						array(),
						array('id' => 'DESC')
					 );

			// pagination
			$paginator = $this->get('knp_paginator');
		    $entries = $paginator->paginate(
		        $entries,
		        $request->query->getInt('page', 1),
		        $this->getParameter("pagination_page_limit")
		    );

			return $this->render("@ReaccionCMSAdminBundle/entries/list.html.twig",
				[
					'entries' => $entries
				]
			);
		}
	}