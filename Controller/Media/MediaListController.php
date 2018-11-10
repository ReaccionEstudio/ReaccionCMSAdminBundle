<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Media;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;

	class MediaListController extends Controller
	{
		public function index(Request $request)
		{
			
			
			return $this->render("@ReaccionCMSAdminBundle/media/list.html.twig",
				[
					
				]
			);
		}
	}