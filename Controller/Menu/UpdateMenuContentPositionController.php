<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Menu;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Services\Menu\MenuService;

	class UpdateMenuContentPositionController extends Controller
	{
		public function index(Menu $menu, MenuContent $menuContent, String $action)
		{
			// TODO: create method in MenuContentService
			$sideEntityToUpdate = null;
			$sideEntityQueryFilters = [];
			$parent = $menuContent->getParent();
			$em = $this->getDoctrine()->getManager();
			$currentMenuItemPosition = $menuContent->getPosition();
			$menuContentService = $this->get("reaccion_cms_admin.menu_content");

			if($parent)
			{
				$sideEntityQueryFilters['parent'] = $parent;
			}

			if($action == "increase")
			{
				$newMenuItemPosition = $currentMenuItemPosition + 1;
				$sideEntityQueryFilters['position'] = $newMenuItemPosition;
			}
			else if($action == "decrease")
			{
				$newMenuItemPosition = $currentMenuItemPosition - 1;
				$sideEntityQueryFilters['position'] = $newMenuItemPosition;
			}
			
			// get menu content entity with the current position
			$sideEntityToUpdate = $em->getRepository(MenuContent::class)->findOneBy($sideEntityQueryFilters);

			if( ! empty($sideEntityToUpdate) )
			{
				$menuContentService->updateMenuContentPosition($sideEntityToUpdate, $currentMenuItemPosition);
			}

			// set new position to the entity
			$menuContentService->updateMenuContentPosition($menuContent, $newMenuItemPosition);

			return $this->redirectToRoute("reaccion_cms_admin_appearance_menu_content", [ 'menu' => $menu->getId() ]);
		}
	}