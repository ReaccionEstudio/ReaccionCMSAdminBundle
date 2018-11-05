<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\PageContent;

	use Doctrine\ORM\EntityManagerInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;

	/**
	 * Service to manage the page content positions.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class PageContentPosition
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
		public function __construct(EntityManagerInterface $em)
		{
			$this->em = $em;
		}

		/**
		 * Get next content position for the specified page
		 *
		 * @param 	Page 		$page 				Current page
		 * @return 	Integer 	$nextPosition 		Next content position
		 */
		public function getNextPosition(Page $page)
		{
			$nextPosition = 0;

			foreach($page->getContent() as $content)
			{
				$contentPosition = $content->getPosition();

				if($contentPosition > $nextPosition) 
				{
					$nextPosition = $contentPosition;
				}
			}

			$nextPosition = $nextPosition + 1;

			return $nextPosition;
		}
	}