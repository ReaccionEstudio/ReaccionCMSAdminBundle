<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages\Content;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;

	use Cocur\Slugify\Slugify;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageContentType;

	class PageContentDetailController extends Controller
	{
		public function index(PageContent $content, Request $request, TranslatorInterface $translator)
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
				$successMessage = $translator->trans(
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