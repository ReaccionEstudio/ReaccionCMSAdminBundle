<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Dashboard;

	use Doctrine\ORM\EntityManager;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;

	/**
	 * Dashboard service
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class DashboardService
	{
		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManager
		 */
		private $em;

		/**
		 * @var String
		 *
		 * Upload directory path
		 */
		private $uploadDir;

		/**
		 * Constructor
		 */
		public function __construct(EntityManager $em, String $uploadDir)
		{
			$this->em = $em;
			$this->uploadDir = $uploadDir;
		}

		/**
		 * Return dashboard widgets statistics
		 *
		 * @return Array 	[type] 	Widgets statistics array
		 */
		public function getWidgetsStats() : Array
		{
			// DQLs
			$totalPagesDql = "SELECT COUNT(p.id) AS total 
							  FROM App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page p ";

			$totalPageContentDql =  "SELECT COUNT(p.id) AS total 
									 FROM App\ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent p ";

			$totalUsersDql =  "SELECT COUNT(u.id) AS total 
							   FROM App\ReaccionEstudio\ReaccionCMSBundle\Entity\User u ";
			
			$totalMediaDql =  "SELECT COUNT(q.id) AS total 
							   FROM App\ReaccionEstudio\ReaccionCMSBundle\Entity\Media q ";

			$totalEntriesDql = "SELECT COUNT(e.id) AS total 
							    FROM App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry e ";

			// query results
			$totalPagesResult = $this->em->createQuery($totalPagesDql)->getSingleResult();
			$totalPageContentResult = $this->em->createQuery($totalPageContentDql)->getSingleResult();
			$totalUsersResult = $this->em->createQuery($totalUsersDql)->getSingleResult();
			$totalMediaResult = $this->em->createQuery($totalMediaDql)->getSingleResult();
			$totalEntriesResult = $this->em->createQuery($totalEntriesDql)->getSingleResult();

			return [
				'pages' => ($totalPagesResult['total']) ? $totalPagesResult['total'] : 0,
				'pageContent' => ($totalPageContentResult['total']) ? $totalPageContentResult['total'] : 0,
				'users' => ($totalUsersResult['total']) ? $totalUsersResult['total'] : 0,
				'media' => ($totalMediaResult['total']) ? $totalMediaResult['total'] : 0,
				'entries' => ($totalEntriesResult['total']) ? $totalEntriesResult['total'] : 0,
			];
		}
	}