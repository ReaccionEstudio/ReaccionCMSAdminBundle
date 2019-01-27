<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages\Content;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;

	use Cocur\Slugify\Slugify;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageContentType;

	class PageContentDetailController extends Controller
	{
		private $translator;
		
		public function __construct(TranslatorInterface $translator)
		{
			$this->translator = $translator;	
		}

		public function index(PageContent $content, Request $request)
		{
			$page = $content->getPage();

			$em = $this->getDoctrine()->getManager();

			// form
			$form = $this->createForm(PageContentType::class, $content);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				// generate slug
				$content->setSlug($content->getName());

				// save
				$em->persist($content);
				$em->flush();

				// update page type if necessary
				$this->get("reaccion_cms_admin.page")->setPageTypeByPageContent($content);

				// flash message
				$successMessage = $this->translator->trans(
									'page_content_form.update_success_message', 
									array('%name%' => $content->getName())
								);
				$this->addFlash('success', $successMessage);

				return 	$this->redirectToRoute(
							'reaccion_cms_admin_pages_detail', 
							array('page' => $page->getId())
						);
			}

			return $this->render("@ReaccionCMSAdminBundle/pages/content/detail.html.twig",
				[
					'page' => $page,
					'content' => $content,
					'form' => $form->createView()
				]
			);
		}
	}