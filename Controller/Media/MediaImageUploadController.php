<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Media;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpFoundation\Response;
	use Symfony\Component\HttpFoundation\JsonResponse;
	use Symfony\Component\Translation\TranslatorInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Media\UploadService;

	class MediaImageUploadController extends Controller
	{
		public function index(Request $request, TranslatorInterface $translator)
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
			}
			catch(\Exception $e)
			{
				$this->get("reaccion_cms.logger")->logException($e, "Error uploading CKEDITOR image.");
				$response = new Response();
				$response->setStatusCode(500);
				return $response;
			}

			$response = [ 'imagePath' => $imagePath ];
			return new JsonResponse($response);
		}
	}