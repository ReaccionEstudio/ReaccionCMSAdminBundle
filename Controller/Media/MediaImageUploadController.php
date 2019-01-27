<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Media;

	use Symfony\Component\Asset\PathPackage;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Services\Media\UploadService;

	class MediaImageUploadController extends Controller
	{
		public function index(Request $request)
		{
			$image = $request->files->get('upload');
			
			if(empty($image)) 
			{
				$this->get("reaccion_cms.logger")->addError("Can't upload CKEDITOR image.");

				$response = new Response();
				$response->setStatusCode(500);
				return $response;
			}
			
			try
			{
				$imagePath = $this->get("reaccion_cms_admin.media_upload")->upload($image, true, false);
				$imageUrl  = $this->generateImageUrl($request, $imagePath);
			}
			catch(\Exception $e)
			{
				$this->get("reaccion_cms.logger")->logException($e, "Error uploading CKEDITOR image.");
				$response = new Response();
				$response->setStatusCode(500);
				return $response;
			}

			$response = [ 'url' => $imageUrl ];
			return new JsonResponse($response);
		}

		private function generateImageUrl($request, $fullImagePath)
		{
			$host = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBasePath();
			$imagePathArray = explode("public/", $fullImagePath);
			$imagePath = $imagePathArray[1];
			return $host . "/" . $imagePath;
		}
	}