<?php
	
	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Tests\Services\Cache;
	
	use PHPUnit\Framework\TestCase;
	use Doctrine\Common\Collections\ArrayCollection;
	use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;

	/**
	 * Page cache service test
	 *
	 * @author Alberto Vian <alberto@reaccionestudio.com>
	 */
	class PageCacheServiceTest extends KernelTestCase
	{
		private $pageCacheService;
		private $page;

		public function setUp()
	    {
	    	$kernel = self::bootKernel();
	    	$this->generatePageTestEntity();
	    	$this->pageCacheService = $kernel->getContainer()->get('reaccion_cms_admin.page_cache_service');
	    }

	    private function generatePageTestEntity()
	    {
	    	$this->page = new Page();
	    	$this->page->setId(1);
	    	$this->page->setSlug("test-page");
	    	$this->page->setSeoTitle("test page");
	    	$this->page->setSeoDescription("test page");
	    	$this->page->setSeoKeywords("test page");

	    	$pageContentCollection = new ArrayCollection();

	    	for($i=0;$i < 3; $i++)
	    	{
		    	$id = $i + 1;
		    	$pageContent = new PageContent();
		    	$pageContent->setId($id);
		    	$pageContent->setName("test content " . $i);
		    	$pageContent->setSlug("test-content-" . $i);
		    	$pageContent->setValue("test " . $i);
		    	$pageContent->setType("text_html");
		    	$pageContent->setPosition($i);
		    	$pageContent->isIsEnabled(true);

	    		$pageContentCollection->add($pageContent);
	    	}
	    	
	    	$this->page->setContent($pageContentCollection);
	    }

	    public function testAddPage()
	    {
	    	$result = $this->pageCacheService->addPage($this->page);
	    	
	    	$this->assertTrue($result);
	    }
	}