<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageType;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\SeoPageType;
	

	class PageDetailController extends Controller
	{
		public function index(Page $page, Request $request)
		{
			$em = $this->getDoctrine()->getManager();

			// forms
			$pageForm = $this->createForm(PageType::class, $page);
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
					$em->persist($page);
					$em->flush();

					$this->addFlash('success', 'Page has been updated correctly.');

				}
				catch(\Exception $e)
				{
					$this->addFlash('error', 'Error updating page: ' . $e->getMessage() . '.');
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