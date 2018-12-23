<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages\Content;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;

	use Cocur\Slugify\Slugify;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageContentType;

	class CreatePageContentController extends Controller
	{
		public function index(Page $page, Request $request, TranslatorInterface $translator)
		{
			$pageContent = new PageContent();
			$em = $this->getDoctrine()->getManager();

			// form
			$form = $this->createForm(PageContentType::class, $pageContent);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				try
				{
					// set page for content
					$pageContent->setPage($page);

					// set content position
					$nextPosition = $this->get("reaccion_cms_admin.page_content_position")->getNextPosition($page);
					$pageContent->setPosition($nextPosition);

					// generate slug
					$pageContent->setSlug($pageContent->getName());
					
					// save
					$em->persist($pageContent);
					$em->flush();

					// update page type if necessary
					$this->get("reaccion_cms_admin.page")->setPageTypeByPageContent($pageContent);

					// flash message
					$successMessage = $translator->trans('page_content_form.create_success_message');
					$this->addFlash('success', $successMessage);

					return 	$this->redirectToRoute(
							'reaccion_cms_admin_pages_detail', 
							array('page' => $page->getId())
						);
				}
				catch(\Exception $e)
				{
					$errorMssg = $translator->trans(
												'page_content_form.create_error_message', 
												[ 
													'%name%' => $pageContent->getName(),
													'%error%' => $e->getMessage() 
												]
											);
					$this->addFlash('error', $errorMssg);
				}
			}

			return $this->render("@ReaccionCMSAdminBundle/pages/content/create.html.twig",
				[
					'page' => $page,
					'form' => $form->createView()
				]
			);
		}
	}