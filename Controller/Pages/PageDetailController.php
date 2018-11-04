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
		public function index(Page $page)
		{
			// forms
			$pageForm = $this->createForm(PageType::class, $page);
			$seoPageForm = $this->createForm(SeoPageType::class, $page);

			return $this->render("@ReaccionCMSAdminBundle/pages/detail.html.twig",
				[
					'page' => $page,
					'pageForm' => $pageForm->createView(),
					'seoPageForm' => $seoPageForm->createView()
				]
			);
		}
	}