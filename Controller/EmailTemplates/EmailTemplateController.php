<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\EmailTemplates;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\EmailTemplate;

	class EmailTemplateController extends Controller
	{
		public function index(Request $request)
		{
			$em = $this->getDoctrine()->getManager();
			$emailTemplates = $em->getRepository(EmailTemplate::class)->findBy(
						array(),
						array('id' => 'DESC')
					);

			// pagination
			$paginator = $this->get('knp_paginator');
		    $emailTemplates = $paginator->paginate(
		        $emailTemplates,
		        $request->query->getInt('page', 1),
		        $this->getParameter("pagination_page_limit")
		    );

			return $this->render("@ReaccionCMSAdminBundle/emailTemplates/list.html.twig",
				[
					'emailTemplates' => $emailTemplates
				]
			);
		}
	}