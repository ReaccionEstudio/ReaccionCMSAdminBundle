<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageType;

	class CreatePageController extends Controller
	{
		public function index(Request $request)
		{
			$page = new Page();
			$em = $this->getDoctrine()->getManager();

			// form
			$form = $this->createForm(PageType::class, $page);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				echo 'ok';
			}

			return $this->render("@ReaccionCMSAdminBundle/pages/form.html.twig",
				[
					'form' => $form->createView()
				]
			);
		}
	}