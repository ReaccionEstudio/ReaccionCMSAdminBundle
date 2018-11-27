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
				$em = $this->getDoctrine()->getManager();

				try
				{
					// set Value
					if(($form['type']->getData() == "url") )
					{
						$menuValue = $form['urlValue']->getData();
					}
					else if($form['type']->getData() == "page")
					{
						$page = $form['pageValue']->getData();
						$menuValue = $page->getId();
					}

					$menu->setValue($menuValue);

					// set next position
					$nextPosition = $this->get("reaccion_cms_admin.menu")->getNextItemPosition($parent);
					$menu->setPosition($nextPosition);

					if($parent)
					{
						$parentMenu = $em->getRepository(Menu::class)->findOneBy(['id' => $parent]);
						
						if($parentMenu) 
						{
							$menu->setParent($parentMenu);
						}
					}

					// save
					$em->persist($menu);
					$em->flush();

					$this->addFlash('success', $translator->trans('menu_form.create_success_message') );
					return $this->redirectToRoute('reaccion_cms_admin_preferences_menu');
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