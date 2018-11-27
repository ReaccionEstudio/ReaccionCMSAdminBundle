<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Menu;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Menu\MenuType;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Menu\MenuService;

	class MenuDetailController extends Controller
	{
		public function index(Menu $menu, Request $request, TranslatorInterface $translator)
		{
			$em = $this->getDoctrine()->getManager();

			// form params
			$formParamKey 	 =  ($menu->getType() == "url") ? 'urlValue' : 'pageValue';
			$formParamsValue =  ($menu->getType() == "url") 
								? $menu->getValue() 
								: $em->getRepository(Page::class)->findOneBy(['id' => $menu->getValue() ]);

			$formParams = [ $formParamKey => $formParamsValue, 'mode' => 'edit' ];

			// form
			$form = $this->createForm(MenuType::class, $menu, $formParams);
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

					$menu->setValue($menuValue);

					// save
					$em->persist($menu);
					$em->flush();

					$this->addFlash('success', $translator->trans('menu_form.update_success_message') );
					return $this->redirectToRoute('reaccion_cms_admin_preferences_menu');
				}
				catch(\Exception $e)
				{
					$errMssg =  $translator->trans(
									"menu_form.update_error_message", 
									array(
										'%name%' => $form['name']->getData(),
										'%error%' => $e->getMessage()
									) 
								);
					$this->addFlash('error', $errMssg);
				}
			}

			return $this->render("@ReaccionCMSAdminBundle/menu/detail.html.twig",
				[
					'form' => $form->createView()
				]
			);
		}
	}