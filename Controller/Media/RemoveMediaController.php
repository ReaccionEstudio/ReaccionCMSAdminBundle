<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Media;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Media;

	class RemoveMediaController extends Controller
	{
		public function index(Media $media, TranslatorInterface $translator)
		{
			if(empty($media))
			{
				throw new NotFoundHttpException("Page content not found");
			}

			$mediaName = $media->getName();
			$em = $this->getDoctrine()->getManager();

			try
			{
				// remove server files
				$paths = [
					$media->getPath(),
					$media->getLargePath(),
					$media->getMediumPath(),
					$media->getSmallPath()
				];

				foreach($paths as $filePath)
				{
					$filePath = $this->getParameter("reaccion_cms_admin.upload_dir") . $filePath;

					if( ! file_exists($filePath) ) continue;

					unlink($filePath);
				}

				$em->remove($media);
				$em->flush();

				$this->addFlash('success', $translator->trans('media_detail.removed_media_successful') );

				return $this->redirectToRoute('reaccion_cms_admin_media_index');
			}
			catch(\Exception $e)
			{
				// TODO: log error
				$errorMssg = $translator->trans(
								'media_detail.removed_media_error', 
								[
									'%name%' => $mediaName, 
									'%error%' => $e->getMessage()
								]
							 );

				$this->addFlash('error', $errorMssg);

				return $this->redirectToRoute('reaccion_cms_admin_media_detail', ['media' => $media->getId() ]);
			}
		}
	}