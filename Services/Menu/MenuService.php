<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Menu;

	use Doctrine\ORM\EntityManager;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;

	/**
	 * Menu service
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class MenuService
	{
		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManager
		 */
		private $em;

		/**
		 * Constructor
		 */
		public function __construct(EntityManager $em)
		{
			$this->em = $em;
		}

		/**
		 * Build nested array with menu items
		 *
		 * @param  Array  $menu  	 Menu items
		 * @return Array  $nested    Menu nested array
		 */
		public function buildNestedArray(Array $arrayToSearch) : Array
		{
			$nested = array();
			$source = array();

			// create source array
			foreach($arrayToSearch as $menuItem)
			{
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
					 FROM  App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu m 
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
	}