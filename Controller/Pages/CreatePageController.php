<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;

	use Cocur\Slugify\Slugify;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageType;

	class CreatePageController extends Controller
	{
		public function index(Request $request, TranslatorInterface $translator)
		{
			$page = new Page();

			// form
			$form = $this->createForm(PageType::class, $page);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				try
				{
					if($form['mainPage']->getData() == true)
					{
						$this->get("reaccion_cms_admin.page")->resetMainPage();
					}

					// generate slug
					$slugify = new Slugify();
					$slug = $slugify->slugify($page->getName());
					$page->setSlug($slug);

					// save
					$em = $this->getDoctrine()->getManager();
					$em->persist($page);
					$em->flush();

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