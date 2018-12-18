<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Page;

	use Doctrine\ORM\EntityManagerInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page as PageEntity;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Constants\PageContentTypes;

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
					// TODO: log
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
				// TODO: log
			}
		}
	}