<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Cache;

	use Doctrine\ORM\EntityManagerInterface;
	use Symfony\Component\HttpFoundation\RequestStack;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use App\ReaccionEstudio\ReaccionCMSBundle\Helpers\CacheHelper;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Cache\CacheService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Config\ConfigService;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Entries\EntryService;
	use App\ReaccionEstudio\ReaccionCMSBundle\EntryView\EntryViewVarsFactory;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Comment\GetCommentsAsArray;
	use App\ReaccionEstudio\ReaccionCMSBundle\DataTransformer\Page\PageDataTransformer;
	use App\ReaccionEstudio\ReaccionCMSBundle\DataTransformer\Entry\EntryDataTransformer;	
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\DynamicRouting\DynamicRoutingManager;

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
		 * @var RequestStack
		 *
		 * RequestStack service
		 */
		private $request;

		/**
		 * @var ConfigService
		 *
		 * Config service
		 */
		private $config;

		/**
		 * @var Array 
		 *
		 * Generated page data
		 */
		private $generatedPageData = [];

		/**
		 * Constructor
		 */
		public function __construct(CacheService $cache, EntityManagerInterface $em, EntryService $entryService, RequestStack $request, ConfigService $config)
		{
			$this->em 			= $em;
			$this->cache 		= $cache;
			$this->config 		= $config;
			$this->entryService = $entryService;
			$this->request 		= $request->getCurrentRequest();
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
		 * Get page data from cache
		 *
		 * @param 	String 	$slug 		Page slug
		 * @return  Array 	$pageData 	Page data array
		 */
		public function getPage(String $slug, Bool $cache = true ) : Array
		{
			// get cached page data if exists
			$pageCacheKey = $this->getCacheKeyForPage($slug);
			
			if($cache && $this->cache->hasItem($pageCacheKey))
			{
				$cachedPage = $this->cache->get($pageCacheKey);

				if($cachedPage['type'] == "entry") 
				{
					// load comments
					$cachedPage = $this->appendCommentsInPage($cachedPage);
				}

				return $cachedPage;
			}

			// get page entity if exists
			$page = $this->getPageEntity($slug);
			
			if(empty($page)) return [];

			// save page data in cache
			if($page instanceof Page)
			{
				$this->addPage($page);
			}

			// return generated page data
			return $this->generatedPageData;
		}

		/**
		 * Get page entity from database by slug
		 *
		 * @param  String 		$slug 	Page slug
		 * @return Page | null 	$page 	Page entity
		 */
		public function getPageEntity(String $slug)
		{
			// get page from database
			$pageFilters = ['slug' => $slug, 'isEnabled' => true ];
			$page = $this->em->getRepository(Page::class)->findOneBy($pageFilters);

			return $page;
		}

		/**
		 * Get entry detail
		 * 
		 * @param 	String 	$slug 		Page slug
		 * @return  Array 	$pageData 	Page data array
		 */
		public function getEntryDetailPage(String $slug, Bool $cache = true) : Array
		{
			// get cached page data if exists
			$pageCacheKey = $this->getCacheKeyForPage($slug . "_entry");

			if($cache && $this->cache->hasItem($pageCacheKey))
			{
				return $this->cache->get($pageCacheKey);
			}

			// get page from database
			$pageFilters = ['slug' => $slug, 'enabled' => true ];
			$entry = $this->em->getRepository(Entry::class)->findOneBy($pageFilters);

			if(empty($entry)) return [];

			// create page entity
			$entryDataTransformer = new EntryDataTransformer($entry);
			$page = $entryDataTransformer->getPageEntity($this->entryService);
			
			// save page data in cache
			$this->addPage($page);

			// load comments
			$this->generatedPageData = $this->appendCommentsInPage($this->generatedPageData);

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
		 * Refresh page cache
		 */
		public function refreshPageCache(String $slug) : Array
		{
			$pageData = $this->getPage($slug, false);

			if(empty($pageData))
			{
				$pageData = $this->getEntryDetailPage($slug, false);
			}

			return $pageData;
		}

		/**
		 * Generate page data as Array
		 *
		 * @param 	Page 	$page 		Page entity
		 * @return 	void 	[type]
		 */
		private function generateArrayPageData(Page $page) : void
		{
			$pageDataTransformer = new PageDataTransformer($page);
			$this->generatedPageData = $pageDataTransformer->getPageViewVars($this->entryService);
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

		/**
		 * Append comments nested array in the page data darray
		 *
		 * @param  Array  $pageData  Page data array
		 * @return Array  $pageData  Page data array with entry comments
		 */
		private function appendCommentsInPage(Array $pageData) : Array
		{
			$options = unserialize($pageData['options']);
			$entryId = $options['entry_id'];
			$pageData['comments'] = $this->getComments($entryId);

			return $pageData;
		}

		/**
		 * Get comments list
		 *
		 * @param  Integer  $entryId 	Entry ID
		 * @return Array 	[type]		Comments array
		 */
		private function getComments(Int $entryId)
		{
			$page = ($this->request->query->get('cp')) ?? 1;
			$getCommentsAsArray = new GetCommentsAsArray($this->em, $entryId, $page, $this->config);
			return $getCommentsAsArray->getComments();
		}

	}