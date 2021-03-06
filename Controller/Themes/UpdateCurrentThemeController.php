<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Themes;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;

	class UpdateCurrentThemeController extends Controller
	{
		private $translator;
		
		public function __construct(TranslatorInterface $translator)
		{
			$this->translator = $translator;	
		}

		public function index(String $themeFolderName)
		{
			$result = $this->get("reaccion_cms_admin.theme")->updateCurrentTheme($themeFolderName);

			// flash message
			$flashName = ($result) ? "success" : "error";
			$translationKey = 'themes.update_theme_' . $flashName . '_message';
			$message = $this->translator->trans($translationKey);
			$this->addFlash($flashName, $message);

			return $this->redirectToRoute('reaccion_cms_admin_appearance_themes');
		}
	}