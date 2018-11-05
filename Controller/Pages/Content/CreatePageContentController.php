<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages\Content;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageContentType;

	class CreatePageContentController extends Controller
	{
		public function index(Page $page, Request $request)
		{
			$pageContent = new PageContent();
			$em = $this->getDoctrine()->getManager();

			// form
			$form = $this->createForm(PageContentType::class, $pageContent);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				// set page for content
				$pageContent->setPage($page);

				// set content position
				$nextPosition = $this->get("reaccion_cms_admin.page_content_position")->getNextPosition($page);
				$pageContent->setPosition($nextPosition);

				// save
				$em->persist($pageContent);
				$em->flush();

				$this->addFlash('success', 'Content was created correctly.');

				return 	$this->redirectToRoute(
							'reaccion_cms_admin_pages_detail', 
							array('page' => $page->getId())
						);
			}

			return $this->render("@ReaccionCMSAdminBundle/pages/content/create.html.twig",
				[
					'page' => $page,
					'form' => $form->createView()
				]
			);
		}
	}