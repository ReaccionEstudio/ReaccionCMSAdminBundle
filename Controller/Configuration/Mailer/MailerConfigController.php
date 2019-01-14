<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Configuration\Mailer;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Configuration\MailerConfigType;

	class MailerConfigController extends Controller
	{
		/**
		 * @var String
		 *
		 * SMTP host
		 */
		private $host;

		/**
		 * @var Integer
		 *
		 * SMTP port
		 */
		private $port = 25;

		/**
		 * @var String
		 *
		 * SMTP authentication type
		 */
		private $authentication = '';

		/**
		 * @var Username
		 *
		 * SMTP username
		 */
		private $username;

		/**
		 * @var String
		 *
		 * SMTP password
		 */
		private $password;

		/**
		 * Index
		 */
		public function index(Request $request, TranslatorInterface $translator)
		{
			$em = $this->getDoctrine()->getManager();
			$configEntities = $em->getRepository(Configuration::class)->findBy(
						array('groups' => 'mailer'),
						array('id' => 'DESC')
					 );

			// create array with the config key names
			$config = [];

			foreach ($configEntities as $configEntity) 
			{
				$key = $configEntity->getName();
				$key = str_replace("mailer_", "", $key);
				$config[$key] = $configEntity->getValue();
			}

			// form
			$form = $this->createForm(MailerConfigType::class, null, ['config' => $config]);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				$this->host 			= $form['host']->getData();
				$this->port 			= $form['port']->getData();
				$this->username 		= $form['username']->getData();
				$this->password 		= $form['password']->getData();
				$this->authentication 	= $form['authentication']->getData();

				// test email connection
				$testConnection = $this->get("reaccion_cms.mailer")->testConnection($this->host, $this->port, $this->authentication, $this->username, $this->password);

				if($testConnection)
				{
					// save config
					$this->updateConfigParams($translator);
				}
				else
				{
					// cannot connect to SMTP server
					$cannotConnectMssg = $translator->trans("preferences_mailer.can_not_connect_to_smtp_server");
					$this->addFlash("error", $cannotConnectMssg);
				}
			}
			
			return $this->render("@ReaccionCMSAdminBundle/configuration/mailer/mailer.html.twig",
				[
					'form' => $form->createView()
				]
			);
		}

		/**
		 * Update config params in database
		 */
		private function updateConfigParams(TranslatorInterface $translator)
		{
			$paramKeys = [ 'host', 'port', 'username', 'password', 'authentication' ];
			$configService = $this->get("reaccion_cms.config");
			$em = $this->getDoctrine()->getManager();

			try
			{
				foreach ($paramKeys as $configKey) 
				{
					$configParam = $configService->getEntity("mailer_" . $configKey, false);

					if(empty($configParam) || ! isset($this->$configKey)) continue;

					$paramValue = $this->$configKey;

					if($configKey == "password")
					{
						// Encrypt password
						$paramValue = $this->get("reaccion_cms.encryptor")->encrypt($paramValue);
					}

					$configParam->setValue($paramValue);

					$em->persist($configParam);
					$em->flush();
				}

				// display success message
				$successTranslation = $translator->trans("preferences_mailer.update_success_message");
				$this->addFlash("success", $successTranslation);
			}
			catch(\Exception $e)
			{
				$this->get("reaccion_cms.logger")->logException($e, "Error updating mailer config paramameter.");
				
				// display error message
				$errorMessage = $translator->trans("preferences_mailer.update_error_message", ['%error%' => $e->getMessage() ]);
				$this->addFlash("error", $errorMessage);
			}
		}
	}