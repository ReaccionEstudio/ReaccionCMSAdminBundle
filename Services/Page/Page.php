<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Page;

	use Doctrine\ORM\EntityManagerInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page as PageEntity;

	/**
	 * Service with utils for Page entity
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class Page
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
		 * Sets 'mainPage' value as false for the main page entity (mainPage = true)
		 *
		 * @return Boolean 	true|false 	Update result
		 */
		public function resetMainPage() : bool
		{
			$mainPage = $this->em->getRepository(PageEntity::class)->findOneBy(
							[ 'mainPage' => true ]
						);

			if(empty($mainPage)) return false;
			
			try
			{
				$mainPage->setMainPage(null);

				$this->em->persist($mainPage);
				$this->em->flush();

				return true;
			}
			catch(\Exception $e)
			{
				// TODO: log error
				return false;
			}
		}

		/**
		 * Get all Page entities
		 *
		 * @param 	Array 		$params 		Query filter parameters
		 * @return 	Array 		[type]			Array page entities
		 */
		public function getPages(Array $params = ['language' => 'en', 'isEnabled' => true]) : Array
		{
			return $this->em->getRepository(PageEntity::class)->findBy($params, ['id' => 'ASC']);
		}
	}