<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Themes;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;

	class ThemesListController extends Controller
	{
		public function index(Request $request)
		{
			$themes = $this->get("reaccion_cms_admin.theme")->listInstalledThemes();

			return $this->render("@ReaccionCMSAdminBundle/themes/list.html.twig",
				[
					'themes' => $themes
				]
			);
		}
	}