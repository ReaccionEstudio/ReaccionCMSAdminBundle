<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Entries;

	use Cocur\Slugify\Slugify;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Entries\EntryType;
	use Symfony\Component\Translation\TranslatorInterface;

	class CreateEntryController extends Controller
	{
		private $translator;
		
		public function __construct(TranslatorInterface $translator)
		{
			$this->translator = $translator;	
		}

		public function index(Request $request)
		{
			$entry = new Entry();

			// form
			$form = $this->createForm(EntryType::class, $entry);
			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) 
			{
				$em = $this->getDoctrine()->getManager();

				try
				{
					// generate resume
					$this->get("reaccion_cms_admin.entry")->generateResume($entry);
					
					// generate slug
					$entry->setSlug($entry->getName());

					// save
					$em->persist($entry);
					$em->flush();

					// success message
					$successMessage = $this->translator->trans('entries_form.create_success_form');
					$this->addFlash('success', $successMessage);

					return $this->redirectToRoute('reaccion_cms_admin_entries_list');
				}
				catch(\Exception $e)
				{
					$this->addFlash('error', $this->translator->trans('entries_form.create_error_form', array('%error%' => $e->getMessage())));
				}
			}
			
			return $this->render("@ReaccionCMSAdminBundle/entries/form.html.twig",
				[
					'form' => $form->createView(),
					'mode' => 'create'
				]
			);
		}
	}