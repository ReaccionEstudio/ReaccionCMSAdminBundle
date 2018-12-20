<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Cache;

	use Doctrine\ORM\EntityManagerInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Helpers\CacheHelper;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Cache\CacheService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Entries\EntryService;
	use App\ReaccionEstudio\ReaccionCMSBundle\PageView\PageViewContentCollection;

	/**
	 * Page cache service.
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class PageCacheService
	{
		/**
		 * @var EntityManagerInterface
		 *
		 * EntityManager
		 */
		private $em;

		/**
		 * @var CacheService
		 *
		 * Cache service
		 */
		private $cache;

		/**
		 * @var EntryService
		 *
		 * Entry service
		 */
		private $entryService;

		/**
		 * @var Array 
		 *
		 * Generated page data
		 */
		private $generatedPageData = [];

		/**
		 * Constructor
		 */
		public function __construct(CacheService $cache, EntityManagerInterface $em, EntryService $entryService)
		{
			$this->em = $em;
			$this->cache = $cache;
			$this->entryService = $entryService;
		}

		/**
		 * Add page data intro cache
		 *
		 * @param 	Page 	$page 			Page entity
		 * @return 	Bool 	true|false
		 */
		public function addPage(Page $page) : bool
		{
			$cachePageKey = $this->getCacheKeyForPage($page->getSlug());

			// generate page array data
			$this->generateArrayPageData($page);
			
			// save in cache
			return $this->cache->set($cachePageKey, $this->generatedPageData);
		}

		/**
		 * Get page data by slug from cache
		 *
		 * @param 	String 	$slug 		Page slug
		 * @return  Array 	$pageData 	Page data array
		 */
		public function getPageBySlug(String $slug) : Array
		{
			// get cached page data if exists
			$pageCacheKey = $this->getCacheKeyForPage($slug);

			if($this->cache->hasItem($pageCacheKey))
			{
				return $this->cache->get($pageCacheKey);
			}

			// get page from database
			$pageFilters = ['slug' => $slug, 'isEnabled' => true ];
			$page = $this->em->getRepository(Page::class)->findOneBy($pageFilters);

			if(empty($page)) return [];

			// save page data in cache
			$this->addPage($page);

			// return generated page data
			return $this->generatedPageData;
		}

		/**
		 * Get main page
		 *
		 * @return Array 	[type] 		Page data
		 */
		public function getMainPage() : Array
		{
			if($this->cache->hasItem("main_page"))
			{
				return $this->cache->get("main_page");
			}
			else
			{
				// get from database
				$mainPage = $this->em->getRepository(Page::class)->findOneBy(
					[
						'mainPage' => true,
						'isEnabled' => true
					]
				);

				// generate page array data
				$this->generateArrayPageData($mainPage);
				
				// save in cache
				$this->cache->set("main_page", $this->generatedPageData);

				// return data
				return $this->generatedPageData;
			}

			return [];
		}

		/**
		 * Generate page data as Array
		 *
		 * @param 	Page 	$page 		Page entity
		 * @return 	void 	[type]
		 */
		private function generateArrayPageData(Page $page) : void
		{
			// create content collection
			$pageViewContentCollection = new PageViewContentCollection($page->getContent(), $this->entryService, $page->getLanguage());
			$contentCollection = $pageViewContentCollection->getContentCollection();

			// page data
			$this->generatedPageData = [
				'id' => $page->getId(),
				'slug' => $page->getSlug(),
				'name' => $page->getName(),
				'type' => $page->getType(),
				'language' => $page->getLanguage(),
				'mainPage' => $page->isMainPage(),
				'templateView' => $page->getTemplateView(),
				'seo' => [
							'title' => $page->getSeoTitle(),
							'description' => $page->getSeoDescription(),
							'keywords' => $page->getSeoKeywords()
						 ],
				'content' => $contentCollection
			];
		}

		/**
		 * Get cache key for current page entity
		 *
		 * @param 	String 	$slug 	Page slug
		 * @return 	String 	[type] 	Cache page key
		 */
		private function getCacheKeyForPage(String $slug) : String
		{
			return (new CacheHelper())->convertSlugToCacheKey($slug, "_page");
		}

	}