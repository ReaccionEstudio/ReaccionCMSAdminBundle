<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Configuration;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Configuration\ConfigType;

	class CreateConfigurationController extends Controller
	{
		private $translator;
		
		public function __construct(TranslatorInterface $translator)
		{
			$this->translator = $translator;	
		}

		public function index(Request $request)
		{
			$config = new Configuration();
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

					$this->addFlash('success', $this->translator->trans('config_form.success_form') );

					return $this->redirectToRoute('reaccion_cms_admin_preferences_configuration');
				}
				catch(\Exception $e)
				{
					$this->addFlash('error', $this->translator->trans('config_form.error_form', array('%error%' => $e->getMessage())));
				}
			}

			return $this->render("@ReaccionCMSAdminBundle/configuration/form.html.twig",
				[
					'form' => $form->createView(),
					'config' => $config,
					'mode' => 'create'
				]
			);
		}
	}