<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages\TranslationGroups;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\PageTranslationGroup;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageTranslationGroupType;

	class CreateTranslationGroupController extends Controller
	{
		public function index(Request $request, TranslatorInterface $translator)
		{
			$pageTranslationGroup = new PageTranslationGroup();

			// form
			$form = $this->createForm(PageTranslationGroupType::class, $pageTranslationGroup);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				try
				{
					// save
					$em = $this->getDoctrine()->getManager();
					$em->persist($pageTranslationGroup);
					$em->flush();

					$this->addFlash('success', $translator->trans('page_translation_group_form.create_success_message') );
					return $this->redirectToRoute('reaccion_cms_admin_pages_index');
				}
				catch(\Exception $e)
				{
					$errMssg =  $translator->trans(
									"page_translation_group_form.create_error_message", 
									array(
										'%name%' => $form['name']->getData(),
										'%error%' => $e->getMessage()
									) 
								);
					$this->addFlash('error', $errMssg);
				}
			}

			return $this->render("@ReaccionCMSAdminBundle/pages/translation_groups/form.html.twig",
				[
					'form' => $form->createView()
				]
			);
		}
	}