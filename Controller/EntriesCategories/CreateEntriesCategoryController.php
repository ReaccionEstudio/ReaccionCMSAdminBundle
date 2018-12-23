<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\EntriesCategories;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\EntryCategory;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\EntriesCategories\EntryCategoryType;
	use Symfony\Component\Translation\TranslatorInterface;

	class CreateEntriesCategoryController extends Controller
	{
		public function index(Request $request, TranslatorInterface $translator)
		{
			$category = new EntryCategory();

			// form
			$form = $this->createForm(EntryCategoryType::class, $category);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				$em = $this->getDoctrine()->getManager();

				try
				{
					// generate slug
					$category->setSlug($category->getName());
					
					// save
					$em->persist($category);
					$em->flush();

					// success message
					$successMessage = $translator->trans('entries_categories_form.success_form');
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
					'mode' => 'create'
				]
			);
		}
	}