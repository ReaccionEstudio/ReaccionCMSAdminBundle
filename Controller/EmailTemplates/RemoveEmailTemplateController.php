<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\EmailTemplates;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\EmailTemplate;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Form\EmailTemplates\EmailTemplateType;

	class RemoveEmailTemplateController extends Controller
	{
		private $translator;
		
		public function __construct(TranslatorInterface $translator)
		{
			$this->translator = $translator;	
		}

		public function index(EmailTemplate $emailTemplate)
		{
			if(empty($emailTemplate))
			{
				throw new NotFoundHttpException("Email template not found");
			}

			$name = $emailTemplate->getName();
			$em = $this->getDoctrine()->getManager();

			try
			{
				$em->remove($emailTemplate);
				$em->flush();

				// success message
				$this->addFlash('success', $this->translator->trans('email_templates.remove_success_message', [ '%name%' => $name ]));
			}
			catch(\Exception $e)
			{
				// log error
				$this->get("reaccion_cms.logger")->logException($e);

				// show error mssg
				$errorMssg = $this->translator->trans(
								'email_templates.remove_error_message', 
								[
									'%name%' => $name, 
									'%error%' => $e->getMessage()
								]
							 );

				$this->addFlash('error', $errorMssg);
			}
			
			return $this->redirectToRoute('reaccion_cms_admin_preferences_email_templates');
		}
	}