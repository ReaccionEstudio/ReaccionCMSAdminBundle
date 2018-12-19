<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Cache;

	use Doctrine\ORM\EntityManagerInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
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
			$cachePageKey = $this->getCacheKeyForPage($page);

			// generate page array data
			$pageData = $this->generateArrayPageData($page);
			
			// save in cache
			return $this->cache->set($cachePageKey, $pageData);
		}

		/**
		 * Generate page data as Array
		 *
		 * @param 	Page 	$page 		Page entity
		 * @return 	Array 	$pageData 	Array data for the page entity
		 */
		private function generateArrayPageData(Page $page) : Array
		{
			// create content collection
			$pageViewContentCollection = new PageViewContentCollection($page->getContent(), $this->entryService, $page->getLanguage());
			$contentCollection = $pageViewContentCollection->getContentCollection();

			// page data
			$pageData = [
				'id' => $page->getId(),
				'slug' => $page->getSlug(),
				'name' => $page->getName(),
				'seo' => [
							'title' => $page->getSeoTitle(),
							'description' => $page->getSeoDescription(),
							'keywords' => $page->getSeoKeywords()
						 ],
				'content' => $contentCollection
			];

			return $pageData;
		}

		/**
		 * Removes cache items form non-existing pages
		 */
		private function clearCacheKeys() : void
		{

		}

		/**
		 * Get cache key for current page entity
		 *
		 * @param 	Page 	$page 	Page entity
		 * @return 	String 	[type] 	Cache page key
		 */
		private function getCacheKeyForPage(Page $page) : String
		{
			$slug = $page->getSlug();
			return str_replace("-", "_", $slug) . "_page";
		}

	}