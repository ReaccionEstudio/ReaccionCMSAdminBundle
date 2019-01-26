<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Entries;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;

	class RemoveEntryController extends Controller
	{
		public function index(Entry $entry, TranslatorInterface $translator)
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

				$this->addFlash('success', $translator->trans('entries_form.remove_success_message') );
			}
			catch(\Exception $e)
			{
				// TODO: log error
				$errorMssg = $translator->trans(
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