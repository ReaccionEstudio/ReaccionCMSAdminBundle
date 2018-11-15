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
		/**
		 * List all media entities
		 *
		 * @param  Media 				$media 		Media entity
		 * @param  Request 				$request 	Request object
		 * @return TwigView | JSON 		[type]		Return a Twig view or a JSON response
		 */
		public function index(Media $media, Request $request)
		{
			if($request->query->get('json')) return $this->getJsonResponse($media);

			return $this->render("@ReaccionCMSAdminBundle/media/detail.html.twig",
				[
					'media' => $media
				]
			);
		}

		/**
		 * Find Media entity by any media path
		 *
		 * @param  Request 			$request 	Request object
		 * @return JsonResponse 	[type] 		JSON object
		 */
		public function findByPath(Request $request)
		{
			$path = $request->request->get('path');

			if(empty($path))
			{
				return new JsonResponse([ 'error' => 'Empty request parameters' ]);
			}

			try
			{
				$dql = "SELECT m 
						FROM  App\ReaccionEstudio\ReaccionCMSBundle\Entity\Media m 
						WHERE m.path = :mediaPath 
						OR m.large_path = :mediaPath 
						OR m.medium_path = :mediaPath 
						OR m.small_path = :mediaPath 
						";

				$em = $this->getDoctrine()->getManager();
				$query = $em->createQuery($dql)
				   			->setParameter("mediaPath", $path);

				$result = $query->getSingleResult();

				return $this->getJsonResponse($result);
			}
			catch(\Exception $e)
			{
				return new JsonResponse(['error' => $e->getMessage()]);
			}
		}

		/**
		 * Return a media object response as JSON
		 *
		 * @param Media 			$media 		Media entity
		 * @param JsonResponse 		[type] 		JSON
		 */
		private function getJsonResponse(Media $media)
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
	}