<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages;

	use Symfony\Component\Form\Form;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSBundle\Constants\Cache;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageType;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\SeoPageType;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Themes\ThemeConfigService;

	class PageDetailController extends Controller
	{
		public function index(Page $page, Request $request, TranslatorInterface $translator)
		{
			// get current template views
			$themeFullPath = $this->get("reaccion_cms.theme")->getFullTemplatePath();
			$themeViews = (new ThemeConfigService($themeFullPath))->getViews();

			// forms
			$pageForm = $this->createForm(
								PageType::class, 
								$page, 
								['templateViews' => $themeViews]
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

					if($pageForm['mainPage']->getData() == true)
					{
						$this->get("reaccion_cms_admin.page")->resetMainPage($language);
					}

					// save
					$em = $this->getDoctrine()->getManager();
					$em->persist($page);
					$em->flush();

					// refresh cache if it is necessary
					$this->refreshPageCache($pageForm);

					// success message
					$this->addFlash('success', $translator->trans('page_form.update_success_message'));

				}
				catch(\Exception $e)
				{
					$errMssg = $translator->trans("page_form.update_error_message", array('%error%' => $e->getMessage()) );
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
		private function refreshPageCache(Form $pageForm) : void
		{
			// TODO: update menu html value for cache
		
			// refresh main page cache
			if($pageForm['mainPage']->getData() == true)
			{
				$language = $pageForm['language']->getData();
				$this->get("reaccion_cms_admin.page_cache_service")->refreshMainPageCache($language);
			}
		}
	}