<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Menu;

	use Doctrine\ORM\EntityManagerInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Page\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Utils\LoggerService;

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
		 * EntityManagerInterface
		 */
		private $em;

		/**
		 * @var Page
		 *
		 * Page service
		 */
		private $page;

		/**
		 * @var LoggerService
		 *
		 * Logger service
		 */
		private $logger;

		/**
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, Page $page, LoggerService $logger)
		{
			$this->em 		= $em;
			$this->page 	= $page;
			$this->logger 	= $logger;
		}

		/**
		 * Build nested array with menu items
		 *
		 * @param  Menu 		$menu 					Menu entity
		 * @param  Boolean 		$hideDisabledItems 		Hide disabled menu items
		 * @param  Boolean 		$addPageSlugs 			Add page slugs
		 * @return Array  		$nested    				Menu nested array
		 */
		public function buildNestedArray(Menu $menu, bool $hideDisabledItems=true, bool $addPageSlugs=false) : Array
		{
			$nested 	= array();
			$source 	= array();
			$menuItems 	= $this->getMenuItemsAsArray($menu, $hideDisabledItems);

			if($addPageSlugs)
			{
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
			}

			// create source array
			foreach($menuItems as $menuItem)
			{
				// replace menu value when menu item type is page with the page slug
				if($addPageSlugs && $menuItem['type'] == "page")
				{
					$pageId = $menuItem['value'];

					if( ! isset($arrayPageSlugs[$pageId])) continue;

					$menuItem['value'] = $arrayPageSlugs[$pageId];
				}

				// add to source array
				$source[$menuItem['id']] = $menuItem;
			}

			// set items position keys
			$source = $this->setItemPositionKeys($source);

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
		 * Set item position keys (isLastPosition and isFirstPosition)
		 *
		 * @param  Array 	$source 	Source array
		 * @return Array 	$source 	Source result array
		 */
		private function setItemPositionKeys(Array $source) : Array
		{
			// 1) items without parents
			$lastPosition = 0;
			$lastPositionItemId = 0;
			$firstPosition = 999999;
			$firstPositionItemId = null;

			foreach($source as $key => $item)
			{
				if($item['parent_id'] != null) continue;
				
				if($lastPosition < $item['position'])
				{
					$lastPosition 		= $item['position'];
					$lastPositionItemId = $key;
				}
				
				if($firstPosition > $item['position'])
				{
					$firstPosition 		= $item['position'];
					$firstPositionItemId = $key;
				}
			}

			// save into source array
			if($lastPositionItemId > 0)
			{
				$source[$lastPositionItemId]['isLastItem'] = true;
			}

			if($firstPositionItemId != null)
			{
				$source[$firstPositionItemId]['isFirstItem'] = true;
			}

			// 2) items with parents
			$itemsGroupedByParentIds = [];

			// group by parent ids
			foreach($source as $key => $item)
			{
				if($item['parent_id'] == null) continue;
				$parentId = $item['parent_id'];
				$item['source_id'] = $key;
				$itemsGroupedByParentIds[$parentId][] = $item;
			}

			foreach($itemsGroupedByParentIds as $itemsGroup)
			{
				$lastPosition = 0;
				$lastPositionItemId = 0;
				$firstPosition = 999999;
				$firstPositionItemId = null;

				foreach($itemsGroup as $item)
				{
					if($lastPosition < $item['position'])
					{
						$lastPosition 		= $item['position'];
						$lastPositionItemId = $item['source_id'];
					}

					if($firstPosition > $item['position'])
					{
						$firstPosition 		 = $item['position'];
						$firstPositionItemId = $item['source_id'];
					}
				}

				// save into source array
				if($lastPositionItemId > 0)
				{
					$source[$lastPositionItemId]['isLastItem'] = true;
				}

				if($firstPositionItemId != null)
				{
					$source[$firstPositionItemId]['isFirstItem'] = true;
				}
			}

			return $source;
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
					m.value,
					m.enabled 
					FROM  App\ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent m 
					LEFT JOIN App\ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent p 
					WITH p.id = m.parent 
					WHERE m.menu = :menuId
					";

			if($hideDisabledItems)
			{
				$dql .= " AND m.enabled = 1";
			}

			$dql .= " ORDER BY m.position ASC ";

			$query = $this->em->createQuery($dql)->setParameter("menuId", $menu->getId());
			return $query->getArrayResult();
		}

		/**
		 * Update menu content position
		 *
		 * @param  MenuContent 	$menuContent 	Menu content entity
		 * @param  Integer 		$position 		Position value 	
		 * @return Boolean 		true|false 		Update result
		 */
		public function updateMenuContentPosition(MenuContent $menuContent, Int $position = 0) : Bool
		{
			try
			{
				$menuContent->setPosition($position);

				$this->em->persist($menuContent);
				$this->em->flush();

				return true;
			}
			catch(\Doctrine\DBAL\DBALException $e)
			{
				$this->logger->logException($e, "Error updating menu item chain position.");
				return false;
			}
		}
	}