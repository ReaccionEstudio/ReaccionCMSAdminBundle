<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages\Content;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
	use Symfony\Component\Translation\TranslatorInterface;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;

	class RemovePageContentController extends Controller
	{
		public function index(PageContent $content, TranslatorInterface $translator)
		{
			// check if page content exists
			if(empty($content))
			{
				throw new NotFoundHttpException("Page content not found");
			}

			// get page content info
			$contentName = $content->getName();
			$page = $content->getPage();

			$em = $this->getDoctrine()->getManager();

			// remove
			try
			{
				$em->remove($content);
				$em->flush();

				// update page type if necessary
				$this->get("reaccion_cms_admin.page")->resetPageType($page);

				// flash message
				$successMessage = $translator->trans(
									'page_content_form.remove_success_message', 
									array('%name%' => $content->getName())
								);
				$this->addFlash('success', $successMessage);

				// redirection
				return 	$this->redirectToRoute(
							'reaccion_cms_admin_pages_detail', 
							array('page' => $page->getId())
						);
			}
			catch(\Exception $e)
			{
				$errorMessage = $translator->trans(
									'page_content_form.remove_error_message',
									array(
										'%name%' => $content->getName(),
										'%error%' => $e->getMessage()
									)
								);
				$this->addFlash('error', $errorMessage);

				return 	$this->redirectToRoute(
							'reaccion_cms_admin_pages_detail', 
							array('page' => $page->getId())
						);
			}
		}
	}