<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Configuration;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration;

	class RemoveConfigurationController extends Controller
	{
		private $translator;
		
		public function __construct(TranslatorInterface $translator)
		{
			$this->translator = $translator;	
		}

		public function index(Configuration $config)
		{
			if(empty($config))
			{
				throw new NotFoundHttpException("Configuration record not found");
			}

			$name = $config->getName();
			$em = $this->getDoctrine()->getManager();

			try
			{
				$em->remove($config);
				$em->flush();

				$this->addFlash('success', $this->translator->trans('config_form.remove_success_form') );
			}
			catch(\Exception $e)
			{
				// TODO: log error
				$errorMssg = $this->translator->trans(
								'config_form.remove_error_form', 
								[
									'%name%' => $name, 
									'%error%' => $e->getMessage()
								]
							 );

				$this->addFlash('error', $errorMssg);
			}
			
			return $this->redirectToRoute('reaccion_cms_admin_preferences_configuration');
		}
	}