<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
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
					if($pageForm['mainPage']->getData() == true)
					{
						$this->get("reaccion_cms_admin.page")->resetMainPage();
					}

					// save
					$em = $this->getDoctrine()->getManager();
					$em->persist($page);
					$em->flush();

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
	}