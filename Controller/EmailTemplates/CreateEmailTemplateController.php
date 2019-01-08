<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\EmailTemplates;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\EmailTemplate;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\EmailTemplates\EmailTemplateType;

	class CreateEmailTemplateController extends Controller
	{
		public function index(Request $request, TranslatorInterface $translator)
		{
			$emailTemplate = new EmailTemplate();

			// form
			$formOptions = ['mode' => 'create'];
			$form = $this->createForm(EmailTemplateType::class, $emailTemplate, $formOptions);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				$em = $this->getDoctrine()->getManager();

				try
				{
					// save
					$em->persist($emailTemplate);
					$em->flush();

					// success message
					$successMessage = $translator->trans('email_templates.create_success_message');
					$this->addFlash('success', $successMessage);

					return $this->redirectToRoute('reaccion_cms_admin_preferences_email_templates');
				}
				catch(\Exception $e)
				{
					$this->addFlash('error', $translator->trans('email_templates.create_error_message', array('%error%' => $e->getMessage())));
				}
			}

			return $this->render("@ReaccionCMSAdminBundle/emailTemplates/form.html.twig",
				[
					'form' => $form->createView(),
					'mode' => 'create'
				]
			);
		}
	}