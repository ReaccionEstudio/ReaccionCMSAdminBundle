<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Users;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\User;

	class UsersListController extends Controller
	{
		public function index(Request $request)
		{
			$em = $this->getDoctrine()->getManager();
			$users = $em->getRepository(User::class)->findBy(
						array(),
						array('id' => 'DESC')
					 );

			// pagination
			$paginator = $this->get('knp_paginator');
		    $users = $paginator->paginate(
		        $users,
		        $request->query->getInt('page', 1),
		        $this->getParameter("pagination_page_limit")
		    );

			return $this->render("@ReaccionCMSAdminBundle/users/list.html.twig",
				[
					'users' => $users
				]
			);
		}
	}