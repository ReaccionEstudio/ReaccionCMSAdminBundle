<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\EntriesCategories;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\EntryCategory;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Form\EntriesCategories\EntryCategoryType;

	class UpdateEntriesCategoryController extends Controller
	{
		public function index(EntryCategory $category, Request $request, TranslatorInterface $translator)
		{
			$em = $this->getDoctrine()->getManager();

			// form
			$form = $this->createForm(EntryCategoryType::class, $category, ['mode' => 'edit']);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				try
				{
					// generate slug
					$category->setSlug($category->getName());
					
					// save
					$em->persist($category);
					$em->flush();

					// success message
					$successMessage = $translator->trans('entries_categories_form.update_success_form');
					$this->addFlash('success', $successMessage);

					return $this->redirectToRoute('reaccion_cms_admin_entries_categories_list');
				}
				catch(\Exception $e)
				{
					$this->addFlash('error', $translator->trans('entries_categories_form.update_error_form', array('%error%' => $e->getMessage())));
				}
			}

			return $this->render("@ReaccionCMSAdminBundle/entries/categories/form.html.twig",
				[
					'form' => $form->createView(),
					'mode' => 'edit',
					'categoryName' => $form['name']->getData()
				]
			);
		}
	}