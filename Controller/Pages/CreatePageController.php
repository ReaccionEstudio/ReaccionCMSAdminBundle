<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages;

	use Cocur\Slugify\Slugify;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageType;
	use App\ReaccionEstudio\ReaccionCMSBundle\Services\Themes\ThemeConfigService;

	class CreatePageController extends Controller
	{
		public function index(Request $request, TranslatorInterface $translator)
		{
			$page = new Page();

			// get current template views
			$themeFullPath = $this->get("reaccion_cms.theme")->getFullTemplatePath();
			$themeViews = (new ThemeConfigService($themeFullPath))->getViews();

			// form
			$form = $this->createForm(PageType::class, $page, ['templateViews' => $themeViews]);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				try
				{
					$language = $pageForm['language']->getData();

					if($form['mainPage']->getData() == true)
					{
						$this->get("reaccion_cms_admin.page")->resetMainPage($language);
					}

					// generate slug
					$page->setSlug($page->getName());

					// save
					$em = $this->getDoctrine()->getManager();
					$em->persist($page);
					$em->flush();

					// refresh main page cache
					if($form['mainPage']->getData() == true)
					{
						$this->get("reaccion_cms_admin.page_cache_service")->refreshMainPageCache($language);
					}

					$this->addFlash('success', $translator->trans('page_form.create_success_message') );
					return $this->redirectToRoute('reaccion_cms_admin_pages_index');
				}
				catch(\Exception $e)
				{
					$errMssg =  $translator->trans(
									"page_form.create_error_message", 
									array(
										'%name%' => $form['name']->getData(),
										'%error%' => $e->getMessage()
									) 
								);
					$this->addFlash('error', $errMssg);
				}
			}

			return $this->render("@ReaccionCMSAdminBundle/pages/form.html.twig",
				[
					'form' => $form->createView()
				]
			);
		}
	}