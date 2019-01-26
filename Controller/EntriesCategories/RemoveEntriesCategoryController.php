<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\EntriesCategories;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use ReaccionEstudio\ReaccionCMSBundle\Entity\EntryCategory;

	class RemoveEntriesCategoryController extends Controller
	{
		public function index(EntryCategory $category, Request $request, TranslatorInterface $translator)
		{
			$em = $this->getDoctrine()->getManager();

			if(empty($category))
			{
				throw new NotFoundHttpException("Entries category item not found");
			}

			$name = $category->getName();

			try
			{
				// remove
				$em->remove($category);
				$em->flush();

				// flash message
				$this->addFlash('success', $translator->trans('entries_categories_form.remove_success_message', array('%name%' => $name)) );
			}
			catch(\Exception $e)
			{
				$errorMssg = $translator->trans('entries_categories_form.remove_error_message', array('%name%' => $name, '%error%' => $e->getMessage()));
				$this->addFlash('error', $errorMssg);
			}

			return 	$this->redirectToRoute('reaccion_cms_admin_entries_categories_list');
		}
	}