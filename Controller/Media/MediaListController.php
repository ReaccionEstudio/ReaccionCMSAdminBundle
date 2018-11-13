<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Media;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Media\ListMediaService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Media;

	class MediaListController extends Controller
	{
		public function index(Request $request)
		{
			$em = $this->getDoctrine()->getManager();
			$media = $em->getRepository(Media::class)->findAll();

			// pagination
			$paginator = $this->get('knp_paginator');
		    $media = $paginator->paginate(
		        $media,
		        $request->query->getInt('media', 1),
		        $this->getParameter("pagination_page_limit")
		    );

			return $this->render("@ReaccionCMSAdminBundle/media/list.html.twig",
				[
					'media' => $media
				]
			);
		}
	}