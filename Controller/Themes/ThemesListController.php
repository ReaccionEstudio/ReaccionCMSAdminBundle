<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Themes;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;

	class ThemesListController extends Controller
	{
		public function index(Request $request)
		{
			return $this->render("@ReaccionCMSAdminBundle/themes/list.html.twig");
		}
	}