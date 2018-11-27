<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Menu;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;

	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Menu\MenuService;

	class RemoveMenuController extends Controller
	{
		public function index(Menu $menu, Request $request, TranslatorInterface $translator)
		{
			$em = $this->getDoctrine()->getManager();

			if(empty($menu))
			{
				throw new NotFoundHttpException("Menu item not found");
			}

			$menuItemName = $menu->getName();

			try
			{
				// remove
				$em->remove($menu);
				$em->flush();

				$this->addFlash('success', $translator->trans('menu_form.remove_success_message', array('%name%' => $menuItemName)) );
			}
			catch(\Exception $e)
			{
				$errorMssg = $translator->trans('menu_form.remove_error_message', array('%name%' => $menuItemName, '%error%' => $e->getMessage()));
				$this->addFlash('error', $errorMssg);
			}

			return 	$this->redirectToRoute('reaccion_cms_admin_preferences_menu');
		}
	}