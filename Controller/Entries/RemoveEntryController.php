<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Entries;

	use Symfony\Component\HttpFoundation\Request;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
	use Symfony\Component\Translation\TranslatorInterface;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;

	class RemoveEntryController extends Controller
	{
		private $translator;
		
		public function __construct(TranslatorInterface $translator)
		{
			$this->translator = $translator;	
		}

		public function index(Entry $entry)
		{
			if(empty($entry))
			{
				throw new NotFoundHttpException("Entry not found");
			}

			$name = $entry->getName();
			$em = $this->getDoctrine()->getManager();

			try
			{
				$em->remove($entry);
				$em->flush();

				$this->addFlash('success', $this->translator->trans('entries_form.remove_success_message') );
			}
			catch(\Exception $e)
			{
				// TODO: log error
				$errorMssg = $this->translator->trans(
								'entries_form.remove_error_message', 
								[
									'%name%' => $name, 
									'%error%' => $e->getMessage()
								]
							 );

				$this->addFlash('error', $errorMssg);
			}
			
			return $this->redirectToRoute('reaccion_cms_admin_entries_list');
		}
	}