<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Menu;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent;

	class RemoveMenuContentController extends Controller
	{
		public function index(Menu $menu, MenuContent $menuContent, Request $request, TranslatorInterface $translator)
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

				// update menu html value for cache
				$this->get("reaccion_cms.menu")->updateMenuHtmlCache($menu);

				// flash message
				$this->addFlash('success', $translator->trans('menu_content_form.remove_success_message', array('%name%' => $menuItemName)) );
			}
			catch(\Exception $e)
			{
				$errorMssg = $translator->trans('menu_content_form.remove_error_message', array('%name%' => $menuItemName, '%error%' => $e->getMessage()));
				$this->addFlash('error', $errorMssg);
			}

			return 	$this->redirectToRoute('reaccion_cms_admin_appearance_menu_content', ['menu' => $menu->getId() ]);
		}
	}