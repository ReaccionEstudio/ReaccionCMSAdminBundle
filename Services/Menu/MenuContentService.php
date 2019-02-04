<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Services\Menu;

	use Doctrine\ORM\EntityManagerInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Logger\LoggerServiceInterface;

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
		 * @var LoggerServiceInterface
		 *
		 * Logger service
		 */
		private $logger;

		/**
		 * Constructor
		 */
		public function __construct(EntityManagerInterface $em, LoggerServiceInterface $logger)
		{
			$this->em 		= $em;
			$this->logger 	= $logger;
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
					 FROM  ReaccionEstudio\ReaccionCMSBundle\Entity\MenuContent m 
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