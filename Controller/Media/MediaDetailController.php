<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Media;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Media\MediaType;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Media;

	class MediaDetailController extends Controller
	{
		public function index(Media $media, Request $request)
		{
			if($request->query->get('json'))
			{
				$response = [
					'id' => $media->getId(),
					'name' => $media->getName(),
					'path' => $media->getPath(),
					'largePath' => $media->getLargePath(),
					'mediumPath' => $media->getMediumPath(),
					'smallPath' => $media->getSmallPath(),
					'size' => $media->getSize(),
					'largeSize' => $media->getLargeSize(),
					'mediumSize' => $media->getMediumSize(),
					'smallSize' => $media->getSmallSize(),
					'mimeType' => $media->getMimeType()
				];
				
				return new JsonResponse($response);
			}

			return $this->render("@ReaccionCMSAdminBundle/media/detail.html.twig",
				[
					'media' => $media
				]
			);
		}
	}