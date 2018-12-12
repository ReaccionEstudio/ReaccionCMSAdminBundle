<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Menu;

	use Cocur\Slugify\Slugify;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Menu\MenuType;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Menu\MenuService;

	class MenuCreateController extends Controller
	{
		public function index(Request $request, TranslatorInterface $translator)
		{
			$menu = new Menu();

			// form
			$form = $this->createForm(MenuType::class, $menu);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				$em = $this->getDoctrine()->getManager();

				try
				{
					// generate slug
					$slugify = new Slugify();
					$slug = $slugify->slugify($menu->getName());
					$menu->setSlug($slug);

					// save
					$em->persist($menu);
					$em->flush();

					// flash message
					$this->addFlash('success', $translator->trans('menu_form.create_success_message') );

					return $this->redirectToRoute('reaccion_cms_admin_appearance_menu');
				}
				catch(\Exception $e)
				{
					$errMssg =  $translator->trans(
									"menu_form.create_error_message", 
									array(
										'%name%' => $form['name']->getData(),
										'%error%' => $e->getMessage()
									) 
								);
					$this->addFlash('error', $errMssg);
				}
			}

			return $this->render("@ReaccionCMSAdminBundle/menu/form.html.twig",
				[
					'form' => $form->createView()
				]
			);
		}
	}