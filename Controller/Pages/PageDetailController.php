<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use Symfony\Component\HttpFoundation\Request;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageType;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\SeoPageType;

	class PageDetailController extends Controller
	{
		public function index(Page $page)
		{
			// form
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