<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Menu;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Menu\MenuContentType;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Menu\MenuService;

	class MenuContentCreateController extends Controller
	{
		public function index(Menu $menu, Int $parent = 0, Request $request, TranslatorInterface $translator)
		{
			$menuContent = new MenuContent();

			// form
			$form = $this->createForm(MenuContentType::class, $menuContent);
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

					$menuContent->setValue($menuValue);
					$menuContent->setMenu($menu);

					// set next position
					$nextPosition = $this->get("reaccion_cms_admin.menu_content")->getNextItemPosition($parent);
					$menuContent->setPosition($nextPosition);

					if($parent)
					{
						$parentMenuContent = $em->getRepository(MenuContent::class)->findOneBy(['id' => $parent]);
						
						if($parentMenuContent) 
						{
							$menuContent->setParent($parentMenuContent);
						}
					}

					// save
					$em->persist($menuContent);
					$em->flush();

					// update menu html value for cache
					$this->get("reaccion_cms.menu")->updateMenuHtmlCache($menu);

					// flash message
					$this->addFlash('success', $translator->trans('menu_form.create_success_message') );

					return $this->redirectToRoute('reaccion_cms_admin_appearance_menu_content', ['menu' => $menu->getId() ]);
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

			return $this->render("@ReaccionCMSAdminBundle/menu/content/form.html.twig",
				[
					'form' => $form->createView(),
					'menu' => $menu
				]
			);
		}
	}