<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Media;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Media;

	class RemoveMediaController extends Controller
	{
		private $translator;
		
		public function __construct(TranslatorInterface $translator)
		{
			$this->translator = $translator;	
		}

		public function index(Media $media)
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
					if(empty($filePath)) continue;

					$filePath = $this->getParameter("reaccion_cms_admin.upload_dir") . $filePath;

					if( ! file_exists($filePath) ) continue;

					unlink($filePath);
				}

				$em->remove($media);
				$em->flush();

				$this->addFlash('success', $this->translator->trans('media_detail.removed_media_successful') );
			}
			catch(\Doctrine\DBAL\DBALException $e)
			{
				$this->get("reaccion_cms.logger")->logException($e, "Error removing media entity.");

				$errorMssg = $this->translator->trans(
								'media_detail.removed_media_error', 
								[
									'%name%' => $mediaName, 
									'%error%' => $e->getMessage()
								]
							 );

				$this->addFlash('error', $errorMssg);
			}
			catch(\Exception $e)
			{
				$this->get("reaccion_cms.logger")->logException($e, "Error removing media files.");

				$errorMssg = $this->translator->trans(
								'media_detail.removed_media_error', 
								[
									'%name%' => $mediaName, 
									'%error%' => $e->getMessage()
								]
							 );

				$this->addFlash('error', $errorMssg);
			}
			
			return $this->redirectToRoute('reaccion_cms_admin_media_index');
		}
	}