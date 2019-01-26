<?php

	namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Translation\TranslatorInterface;

	use ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageType;
	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

	class RemovePageController extends Controller
	{
		public function index(Page $page, TranslatorInterface $translator)
		{
			$em = $this->getDoctrine()->getManager();

			if(empty($page))
			{
				throw new NotFoundHttpException("Page content not found");
			}

			$pageName = $page->getName();

			try
			{
				// remove
				$em->remove($page);
				$em->flush();

				$this->addFlash('success', $translator->trans('page_form.remove_success_message', array('%name%' => $pageName)) );

				return $this->redirectToRoute('reaccion_cms_admin_pages_index');
			}
			catch(\Exception $e)
			{
				$errorMssg = $translator->trans('page_form.remove_error_message', array('%name%' => $pageName, '%error%' => $e->getMessage()));
				$this->addFlash('error', $errorMssg);

				return 	$this->redirectToRoute(
							'reaccion_cms_admin_pages_detail', 
							array('page' => $page->getId())
						);
			}
		}
	}