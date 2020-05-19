<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Menu;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Menu\MenuContentType;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Services\Menu\MenuService;

	class MenuContentDetailController extends Controller
	{
		private $translator;
		
		public function __construct(TranslatorInterface $translator)
		{
			$this->translator = $translator;	
		}
		
		public function index(Menu $menu, MenuContent $menuContent, Request $request)
		{
			$em = $this->getDoctrine()->getManager();

			// form params
			$formParamKey 	 =  ($menuContent->getType() == "url") ? 'urlValue' : 'pageValue';
			$formParamsValue =  ($menuContent->getType() == "url") 
								? $menuContent->getValue() 
								: $em->getRepository(Page::class)->findOneBy(['id' => $menuContent->getValue() ]);

			$formParams = [ $formParamKey => $formParamsValue, 'mode' => 'edit' ];

			// form
			$form = $this->createForm(MenuContentType::class, $menuContent, $formParams);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
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

					// save
					$em->persist($menuContent);
					$em->flush();

					// flash message
					$this->addFlash('success', $this->translator->trans('menu_form.update_success_message') );
					return $this->redirectToRoute('reaccion_cms_admin_appearance_menu_content', ['menu' => $menu->getId() ]);
				}
				catch(\Exception $e)
				{
					$errMssg =  $this->translator->trans(
									"menu_form.update_error_message", 
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
