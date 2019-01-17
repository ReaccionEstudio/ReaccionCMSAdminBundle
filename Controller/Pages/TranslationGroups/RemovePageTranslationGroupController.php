<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages\TranslationGroups;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;

	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\PageTranslationGroup;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageTranslationGroupType;

	class RemovePageTranslationGroupController extends Controller
	{
		public function index(PageTranslationGroup $pageTranslationGroup, TranslatorInterface $translator)
		{
			$em = $this->getDoctrine()->getManager();

			if(empty($pageTranslationGroup))
			{
				throw new NotFoundHttpException("PageTranslationGroup not found");
			}

			$name = $pageTranslationGroup->getName();

			try
			{
				// remove
				$em->remove($pageTranslationGroup);
				$em->flush();

				$this->addFlash('success', $translator->trans('page_translation_group_form.remove_success_message', array('%name%' => $name)) );

				return $this->redirectToRoute('reaccion_cms_admin_pages_index');
			}
			catch(\Exception $e)
			{
				$errorMssg = $translator->trans('page_translation_group_form.remove_error_message', array('%name%' => $name, '%error%' => $e->getMessage()));
				$this->addFlash('error', $errorMssg);

				return 	$this->redirectToRoute(
							'reaccion_cms_admin_pages_index', 
							array('page' => $page->getId())
						);
			}
		}
	}