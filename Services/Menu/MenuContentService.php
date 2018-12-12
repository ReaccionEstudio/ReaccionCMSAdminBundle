<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Menu;

	use Doctrine\ORM\EntityManager;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Page\Page;

	/**
	 * Menu service
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class MenuContentService
	{
		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManager
		 */
		private $em;

		/**
		 * @var Page
		 *
		 * Page service
		 */
		private $page;

		/**
		 * Constructor
		 */
		public function __construct(EntityManager $em, Page $page)
		{
			$this->em = $em;
			$this->page = $page;
		}

		/**
		 * Build nested array with menu items
		 *
		 * @param  Menu 		$menu 					Menu entity
		 * @param  Boolean 		$hideDisabledItems 		Hide disabled menu items?
		 * @return Array  		$nested    				Menu nested array
		 */
		public function buildNestedArray(Menu $menu, bool $hideDisabledItems=true) : Array
		{
			$nested 	= array();
			$source 	= array();
			$menuItems 	= $this->getMenuItemsAsArray($menu, $hideDisabledItems);

			// get all pages
			$pageFilters = [ 'language' => $menu->getLanguage(), 'isEnabled' => true ];
			$pages 		 = $this->page->getPages($pageFilters);

			// create new array to store page slugs
			$arrayPageSlugs = [];

			foreach($pages as $page)
			{
				$key = $page->getId();
				$arrayPageSlugs[$key] = $page->getSlug();
			}

			// create source array
			foreach($menuItems as $menuItem)
			{
				// replace menu value when menu item type is page with the page slug
				if($menuItem['type'] == "page")
				{
					$pageId = $menuItem['value'];

					if( ! isset($arrayPageSlugs[$pageId])) continue;

					$menuItem['value'] = $arrayPageSlugs[$pageId];
				}

				// add to source array
				$source[$menuItem['id']] = $menuItem;
			}

			// create nested array
			foreach ($source as &$s) 
			{
				if ( is_null($s['parent_id']) ) 
				{
					// no parent_id so we put it in the root of the array
					$nested[] = &$s;
				}
				else 
				{
					$pid = $s['parent_id'];
					
					if ( isset($source[$pid]) ) 
					{
						// If the parent ID exists in the source array
						// we add it to the 'children' array of the parent after initializing it.
						if ( ! isset($source[$pid]['children']) ) 
						{
							$source[$pid]['children'] = array();
						}

						$source[$pid]['children'][] = &$s;
					}
				}
			}

			return $nested;
		}

		/**
		 * Get next item position value
		 *
		 * @param  Integer 	$parentId 	Parent menu item id
		 * @return Integer 	[type] 		Next item position
		 */
		public function getNextItemPosition(Int $parentId) : Int
		{
			$dql =  "SELECT (MAX(m.position) + 1) AS next_position 
					 FROM  App\ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent m 
					";

			if($parentId > 0)
			{
				$dql .= "WHERE m.parent = :parent";
			}

			$query = $this->em->createQuery($dql);

			if($parentId > 0)
			{
				$query->setParameter('parent', $parentId);
			}

			$result = $query->getSingleResult();

			return (isset($result['next_position'])) ? $result['next_position'] : 1;
		}

		/**
		 * Get all menu items as array
		 *
		 * @param  Boolean 		$hideDisabledItems 		Hide disabled menu items?
		 * @return Array 		[type] 					Array with all menu items
		 */
		public function getMenuItemsAsArray(Menu $menu, bool $hideDisabledItems=true) : Array
		{
			$dql =  "
					SELECT 
					m.id, 
					p.id AS parent_id, 
					m.name, 
					m.type, 
					m.target, 
					m.position,
					m.value
					FROM  App\ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent m 
					LEFT JOIN App\ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent p 
					WITH p.id = m.parent 
					WHERE m.menu = :menuId
					";

			if($hideDisabledItems)
			{
				$dql .= " AND m.enabled = 1";
			}

			$query = $this->em->createQuery($dql)->setParameter("menuId", $menu->getId());
			return $query->getArrayResult();
		}
	}