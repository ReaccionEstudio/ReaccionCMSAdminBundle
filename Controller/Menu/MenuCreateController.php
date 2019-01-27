<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Menu;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Menu\MenuType;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Services\Menu\MenuService;

	class MenuCreateController extends Controller
	{
		private $translator;
		
		public function __construct(TranslatorInterface $translator)
		{
			$this->translator = $translator;	
		}

		public function index(Request $request)
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
					$menu->setSlug($menu->getName());

					// save
					$em->persist($menu);
					$em->flush();

					// flash message
					$this->addFlash('success', $this->translator->trans('menu_form.create_success_message') );

					return $this->redirectToRoute('reaccion_cms_admin_appearance_menu');
				}
				catch(\Exception $e)
				{
					$errMssg =  $this->translator->trans(
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
					'form' => $form->createView(),
					'mode' => 'create'
				]
			);
		}
	}