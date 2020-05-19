<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Menu;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent;

	class RemoveMenuContentController extends Controller
	{
		private $translator;
		
		public function __construct(TranslatorInterface $translator)
		{
			$this->translator = $translator;	
		}

		public function index(Menu $menu, MenuContent $menuContent, Request $request)
		{
			$em = $this->getDoctrine()->getManager();

			if(empty($menuContent))
			{
				throw new NotFoundHttpException("Menu item not found");
			}

			$menuItemName = $menuContent->getName();

			try
			{
				// remove
				$em->remove($menuContent);
				$em->flush();

				// flash message
				$this->addFlash('success', $this->translator->trans('menu_content_form.remove_success_message', array('%name%' => $menuItemName)) );
			}
			catch(\Exception $e)
			{
				$errorMssg = $this->translator->trans('menu_content_form.remove_error_message', array('%name%' => $menuItemName, '%error%' => $e->getMessage()));
				$this->addFlash('error', $errorMssg);
			}

			return 	$this->redirectToRoute('reaccion_cms_admin_appearance_menu_content', ['menu' => $menu->getId() ]);
		}
	}
