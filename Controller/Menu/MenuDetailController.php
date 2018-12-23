<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Menu;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Menu\MenuType;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Menu\MenuService;

	class MenuDetailController extends Controller
	{
		public function index(Menu $menu, Request $request, TranslatorInterface $translator)
		{
			$em = $this->getDoctrine()->getManager();

			// form params
			$formParams = ['mode' => 'edit'];

			// form
			$form = $this->createForm(MenuType::class, $menu, $formParams);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				try
				{
					// generate slug
					$menu->setSlug($menu->getName());
					
					// save
					$em->persist($menu);
					$em->flush();

					// update menu html value for cache
					$this->get("reaccion_cms.menu")->updateMenuHtmlCache($menu);

					// flash message
					$this->addFlash('success', $translator->trans('menu_form.update_success_message') );
					return $this->redirectToRoute('reaccion_cms_admin_appearance_menu');
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

			return $this->render("@ReaccionCMSAdminBundle/menu/form.html.twig",
				[
					'form' => $form->createView(),
					'menu' => $menu,
					'mode' => 'edit'
				]
			);
		}
	}