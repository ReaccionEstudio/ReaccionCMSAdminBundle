<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages\Content;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageContentType;

	class PageContentDetailController extends Controller
	{
		public function index(PageContent $content, Request $request)
		{
			$page = $content->getPage();

			$em = $this->getDoctrine()->getManager();

			// form
			$form = $this->createForm(PageContentType::class, $content);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				// save
				$em->persist($content);
				$em->flush();

				$this->addFlash('success', 'Content <strong>' . $content->getName() . '</strong> was updated correctly.');

				return 	$this->redirectToRoute(
							'reaccion_cms_admin_pages_detail', 
							array('page' => $page->getId())
						);
			}

			return $this->render("@ReaccionCMSAdminBundle/pages/content/create.html.twig",
				[
					'page' => $page,
					'content' => $content,
					'form' => $form->createView()
				]
			);
		}
	}