<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Configuration;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration;

	class RemoveConfigurationController extends Controller
	{
		public function index(Configuration $config, TranslatorInterface $translator)
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

				$this->addFlash('success', $translator->trans('config_form.remove_success_form') );
			}
			catch(\Exception $e)
			{
				// TODO: log error
				$errorMssg = $translator->trans(
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