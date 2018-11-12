<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Configuration;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Configuration\ConfigType;

	class UpdateConfigurationController extends Controller
	{
		public function index(Configuration $config, Request $request, TranslatorInterface $translator)
		{
			$em = $this->getDoctrine()->getManager();

			// form
			$form = $this->createForm(ConfigType::class, $config);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				try
				{
					// save
					$em->persist($config);
					$em->flush();

					$this->addFlash('success', $translator->trans('config_form.update_success_form') );

					return $this->redirectToRoute('reaccion_cms_admin_preferences_configuration');
				}
				catch(\Exception $e)
				{
					$this->addFlash('error', $translator->trans('config_form.update_error_form', array('%error%' => $e->getMessage())));
				}
			}

			return $this->render("@ReaccionCMSAdminBundle/configuration/form.html.twig",
				[
					'form' => $form->createView()
				]
			);
		}
	}