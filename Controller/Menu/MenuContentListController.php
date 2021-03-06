<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Menu;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Services\Menu\MenuService;

	class MenuContentListController extends Controller
	{
		public function index(Menu $menu, Request $request)
		{
			$nested = $this
						->get("reaccion_cms.menu_content")
						->buildNestedArray($menu, false);

			return $this->render("@ReaccionCMSAdminBundle/menu/content/list.html.twig",
				[
					'menuContent' => $nested,
					'menu' => $menu
				]
			);
		}
	}