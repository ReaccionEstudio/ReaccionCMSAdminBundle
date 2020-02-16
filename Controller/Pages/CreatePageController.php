<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages;

	use Cocur\Slugify\Slugify;
    use ReaccionEstudio\ReaccionCMSBundle\Core\Router\Loader\FileLoader;
    use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageType;
	use ReaccionEstudio\ReaccionCMSBundle\Services\Themes\ThemeConfigService;

	class CreatePageController extends Controller
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

		public function index(Request $request)
		{
			$page = new Page();
			$em = $this->getDoctrine()->getManager();

			// get current template views
			$themeFullPath = $this->get("reaccion_cms.theme")->getFullTemplatePath();
			$themeViews = (new ThemeConfigService($themeFullPath))->getViews();

			// form
			$formOptions = [ 
				'templateViews' => $themeViews, 
				'query' => $request->query->all(),
				'entity_manager' => $em
			];
			$form = $this->createForm(PageType::class, $page, $formOptions);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				try
				{
					$language = $form['language']->getData();

					if($form['mainPage']->getData() == true)
					{
						$this->get("reaccion_cms_admin.page")->resetMainPage($language);
					}

					// generate slug
					$page->setSlug($page->getName());

					// save
					$em->persist($page);
					$em->flush();

                    // TODO: create EVENT
                    $this->get('reaccion_cms.router')->setLoader(FileLoader::class)->updateSchema();

					$this->addFlash('success', $this->translator->trans('page_form.create_success_message') );
					return $this->redirectToRoute('reaccion_cms_admin_pages_index');
				}
				catch(\Exception $e)
				{
					$errMssg =  $this->translator->trans(
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
