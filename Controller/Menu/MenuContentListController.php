<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Menu;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Menu\MenuService;

	class MenuContentListController extends Controller
	{
		public function index(Menu $menu, Request $request, TranslatorInterface $translator)
		{
			$nested = $this
						->get("reaccion_cms_admin.menu_content")
						->buildNestedArray($menu, false);

			return $this->render("@ReaccionCMSAdminBundle/menu/content/list.html.twig",
				[
					'menuContent' => $nested,
					'menu' => $menu
				]
			);
		}
	}