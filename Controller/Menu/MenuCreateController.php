<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Menu;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Menu\MenuType;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Menu\MenuService;

	class MenuCreateController extends Controller
	{
		public function index(Int $parent = 0, Request $request, TranslatorInterface $translator)
		{
			$menu = new Menu();

			// form
			$form = $this->createForm(MenuType::class, $menu);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				
			}

			return $this->render("@ReaccionCMSAdminBundle/menu/form.html.twig",
				[
					'form' => $form->createView()
				]
			);
		}
	}