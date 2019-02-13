<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages;

	use Symfony\Component\Form\Form;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use ReaccionEstudio\ReaccionCMSBundle\Constants\Cache;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageType;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\SeoPageType;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Themes\ThemeConfigService;

	class PageDetailController extends Controller
	{
		/**
		 * @var TranslatorInterface
		 */
		private $translator;

		/** 
		 * Constructor
		 */
		public function __construct(TranslatorInterface $translator)
		{
			$this->translator = $translator;	
		}

		public function index(Page $page, Request $request)
		{
			$em = $this->getDoctrine()->getManager();

			// get current template views
			$themeFullPath = $this->get("reaccion_cms.theme")->getFullTemplatePath();
			$themeViews = (new ThemeConfigService($themeFullPath))->getViews();

			// forms
			$pageForm = $this->createForm(
								PageType::class, 
								$page, 
								[ 
									'templateViews' => $themeViews,
									'entity_manager' => $em,
									'query' => [
										'translationGroup' => $page->getTranslationGroup() 
									]
								]
							);
			$seoPageForm = $this->createForm(SeoPageType::class, $page);

			// handle forms request
			$pageForm->handleRequest($request);
			$seoPageForm->handleRequest($request);

			// update page entity for both forms submitted data
			if( 
				( $pageForm->isSubmitted() && $pageForm->isValid() ) || 
				( $seoPageForm->isSubmitted() && $seoPageForm->isValid() ) 
			) 
			{
				try
				{
					$language = $pageForm['language']->getData();

					if($pageForm['mainPage']->getData() == true && $page->isMainPage() == false)
					{
						$this->get("reaccion_cms_admin.page")->resetMainPage($language);
					}

					// save
					$em->persist($page);
					$em->flush();

					// refresh cache if it is necessary
					$this->refreshCache($pageForm, $seoPageForm, $page);

					// success message
					$this->addFlash('success', $this->translator->trans('page_form.update_success_message'));

				}
				catch(\Exception $e)
				{
					$errMssg = $this->translator->trans("page_form.update_error_message", array('%error%' => $e->getMessage() . "<br /><br />" . $e->getTraceAsString()) );
					$this->addFlash('error', $errMssg);
				}
			}

			return $this->render("@ReaccionCMSAdminBundle/pages/detail.html.twig",
				[
					'page' => $page,
					'pageForm' => $pageForm->createView(),
					'seoPageForm' => $seoPageForm->createView()
				]
			);
		}

		/**
		 * Refresh page cache if it is necessary
		 *
		 * @param  Array  $pageForm 	Page form data
		 * @return void   [type]
		 */
		private function refreshCache(Form $pageForm, Form $seoForm, Page $page) : void
		{
			$pageId = $page->getId();
			$menuService = $this->get("reaccion_cms.menu");
			$pageCacheService = $this->get("reaccion_cms.page_cache_service");

			// update menu html value for cache if this page is inside any menu
			$menu = $menuService->getPageMenu($pageId);

			if($menu)
			{
				$menuService->saveMenuHtmlInCache($menu->getSlug(), $menu->getLanguage());
			}

			// update page cache
			$pageSlug = $seoForm['slug']->getData();
			$pageCacheService->refreshPageCache($pageSlug);
		
			// refresh main page cache
			if($pageForm['mainPage']->getData() == true)
			{
				$language = $pageForm['language']->getData();
				$pageCacheService->refreshMainPageCache($language);
			}
		}
	}