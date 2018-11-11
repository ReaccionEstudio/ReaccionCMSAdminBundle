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

			return $this->render("@ReaccionCMSAdminBundle/media/list.html.twig",
				[
					'media' => $media
				]
			);
		}
	}