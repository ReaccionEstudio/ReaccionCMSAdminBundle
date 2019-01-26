<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Services\Page;

	use Doctrine\ORM\EntityManagerInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Page as PageEntity;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Constants\PageContentTypes;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Utils\Logger\LoggerServiceInterface;

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
			$this->em = $em;
			$this->logger = $logger;
		}

		/**
		 * Sets 'mainPage' value as false for the main page entity (mainPage = true)
		 *
		 * @param  String 	$language 	Page language
		 * @return Boolean 	true|false 	Update result
		 */
		public function resetMainPage(String $language="en") : bool
		{
			$mainPage = $this->em->getRepository(PageEntity::class)->findOneBy(
							[ 
								'mainPage' => true,
								'language' => $language
							]
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
				$this->logger->logException($e);
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

		/**
		 * Set page type depending on the page content entities
		 *
		 * @param  PageContent  	$pageContent 	PageContent entity
		 * @return void 			[type]
		 */
		public function setPageTypeByPageContent(PageContent $pageContent) : void
		{
			// Page type => news
			$needUpdate = false;

			$pageContentTypesForNewsPage = [
				PageContentTypes::PageContentTypesList['page_content_types.entry_categories'],
				PageContentTypes::PageContentTypesList['page_content_types.entries_list']
			];

			// get Page entity
			$page = $pageContent->getPage();

			if( in_array($pageContent->getType(), $pageContentTypesForNewsPage) )
			{
				$page->setType("news");
				$needUpdate = true;
			}
			else
			{
				foreach($page->getContent() as $pageContent)
				{
					if( in_array($pageContent->getType(), $pageContentTypesForNewsPage) )
					{
						$page->setType("news");
						$needUpdate = true;
						break;
					}
				}
			}

			if($needUpdate)
			{
				// update page type value
				try
				{
					$this->em->persist($page);
					$this->em->flush();
				}
				catch(\Exception $e)
				{
					$this->logger->logException($e);
				}
			}
		}

		/**
		 * Reset page type value
		 *
		 * @param 	Page 		$page 		Page entity
		 * @return  void 		[type]
		 */ 
		public function resetPageType(PageEntity $page) : void
		{
			try
			{
				$page->setType("");

				$this->em->persist($page);
				$this->em->flush();
			}
			catch(\Exception $e)
			{
				$this->logger->logException($e);
			}
		}
	}