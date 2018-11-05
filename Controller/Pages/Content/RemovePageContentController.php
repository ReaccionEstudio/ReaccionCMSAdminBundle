<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages\Content;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\PageContent;

	class RemovePageContentController extends Controller
	{
		public function index(PageContent $content)
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

				$this->addFlash('success', 'Content <strong>' . $contentName . '</strong> was removed correctly.');

				return 	$this->redirectToRoute(
							'reaccion_cms_admin_pages_detail', 
							array('page' => $page->getId())
						);
			}
			catch(\Exception $e)
			{
				$this->addFlash('error', 'Error removing <strong>' . $contentName . '</strong> content: ' . $e->getMessage() . '.');

				return 	$this->redirectToRoute(
							'reaccion_cms_admin_pages_detail', 
							array('page' => $page->getId())
						);
			}
		}
	}