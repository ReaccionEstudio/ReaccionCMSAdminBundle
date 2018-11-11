<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Media;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Media\MediaType;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Media;

	class MediaDetailController extends Controller
	{
		public function index(Media $media)
		{
			

			return $this->render("@ReaccionCMSAdminBundle/media/detail.html.twig",
				[
					'media' => $media
				]
			);
		}
	}