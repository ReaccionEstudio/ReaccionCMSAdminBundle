<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Media;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Media\MediaType;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Media\UploadService;

	class CreateMediaController extends Controller
	{
		public function index(Request $request, TranslatorInterface $translator)
		{
			// form
			$form = $this->createForm(MediaType::class);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				$file = $form['attachment']->getData();

				try
				{
					$originalFilename = $file->getClientOriginalName();

					// upload process
					$mediaUploadService = $this->get("reaccion_cms_admin.media_upload");
					$mediaUploadService->upload($file);

					// success message
					$successMessage = $translator->trans('media_create.media_upload_success', array('%filename%' => $originalFilename) );
					$this->addFlash('success', $successMessage);

					return $this->redirectToRoute('reaccion_cms_admin_media_index');
				}
				catch(\Exception $e)
				{
					$this->addFlash('error', $e->getMessage());
				}
			}
			
			return $this->render("@ReaccionCMSAdminBundle/media/form.html.twig",
				[
					'form' => $form->createView()
				]
			);
		}
	}