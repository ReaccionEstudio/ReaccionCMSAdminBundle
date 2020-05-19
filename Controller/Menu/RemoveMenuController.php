<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Menu;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;

	class RemoveMenuController extends Controller
	{
		private $translator;
		
		public function __construct(TranslatorInterface $translator)
		{
			$this->translator = $translator;	
		}

		public function index(Menu $menu, Request $request)
		{
			$em = $this->getDoctrine()->getManager();

			if(empty($menu))
			{
				throw new NotFoundHttpException("Menu not found");
			}

			$menuName = $menu->getName();

			try
			{
				// remove
				$em->remove($menu);
				$em->flush();

				// flash message
				$this->addFlash('success', $this->translator->trans('menu_form.remove_success_message', array('%name%' => $menuName)) );
			}
			catch(\Exception $e)
			{
				$errorMssg = $this->translator->trans('menu_form.remove_error_message', array('%name%' => $menuName, '%error%' => $e->getMessage()));
				$this->addFlash('error', $errorMssg);
			}

			return 	$this->redirectToRoute('reaccion_cms_admin_appearance_menu');
		}
	}
