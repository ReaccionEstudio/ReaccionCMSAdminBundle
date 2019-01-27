<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Configuration;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Configuration\ConfigType;

	class UpdateConfigurationController extends Controller
	{
		private $translator;
		
		public function __construct(TranslatorInterface $translator)
		{
			$this->translator = $translator;	
		}

		public function index(Configuration $config, Request $request)
		{
			$em = $this->getDoctrine()->getManager();

			// form
			$form = $this->createForm(ConfigType::class, $config, ['config' => $config]);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				$configType = $config->getType();
				$configValue = $config->getValue();

				if(isset($form['image']))
				{
					$file = $form['image']->getData();
					$filePath = "";

					if($file !== null)
					{
						$filePath = $this->get("reaccion_cms_admin.media_upload")->upload($file, false, false);
					}
					
					if(strlen($filePath))
					{
						$relativePath = explode("uploads/", $filePath);
						$relativePath = isset($relativePath[1]) ? $relativePath[1] : '';

						$config->setValue($relativePath);
					}
				}

				if($configType == "serialized")
				{
					$serializedData = unserialize($configValue);
					$serializedDataKeys = array_keys($serializedData);
					$arrayFormValues = [];

					foreach($serializedDataKeys as $key)
					{
						if( ! isset($form[$key])) continue;
						$arrayFormValues[$key] = $form[$key]->getData();
					}

					$serializedFormValue = serialize($arrayFormValues);
					$config->setValue($serializedFormValue);
				}

				try
				{
					// save
					$em->persist($config);
					$em->flush();

					// update cache value
					$cacheParamKey = "config." . $config->getName();
					$this->get("reaccion_cms.cache")->set($cacheParamKey, $config->getValue());

					// flash message
					$this->addFlash('success', $this->translator->trans('config_form.update_success_form') );

					return $this->redirectToRoute('reaccion_cms_admin_preferences_configuration');
				}
				catch(\Exception $e)
				{
					$this->addFlash('error', $this->translator->trans('config_form.update_error_form', array('%error%' => $e->getMessage())));
				}
			}

			return $this->render("@ReaccionCMSAdminBundle/configuration/form.html.twig",
				[
					'form' => $form->createView(),
					'config' => $config,
					'mode' => 'edit'
				]
			);
		}
	}