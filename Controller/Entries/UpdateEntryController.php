<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Entries;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Entries\EntryType;
	use Symfony\Component\Translation\TranslatorInterface;

	class UpdateEntryController extends Controller
	{
		public function index(Entry $entry, Request $request, TranslatorInterface $translator)
		{
			// form
			$form = $this->createForm(EntryType::class, $entry, ['mode' => 'edit']);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				$em = $this->getDoctrine()->getManager();

				try
				{
					// save
					$em->persist($entry);
					$em->flush();

					// success message
					$successMessage = $translator->trans('entries_form.update_success_form');
					$this->addFlash('success', $successMessage);

					return $this->redirectToRoute('reaccion_cms_admin_entries_list');
				}
				catch(\Exception $e)
				{
					$this->addFlash('error', $translator->trans('entries_categories_form.update_error_form', array('%error%' => $e->getMessage())));
				}
			}
			
			return $this->render("@ReaccionCMSAdminBundle/entries/form.html.twig",
				[
					'form' => $form->createView(),
					'mode' => 'edit',
					'entryName' => $form['name']->getData()
				]
			);
		}
	}