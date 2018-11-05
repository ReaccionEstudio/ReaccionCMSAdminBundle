<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\HttpFoundation\Request;

	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageType;
	use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

	class RemovePageController extends Controller
	{
		public function index(Page $page)
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

				$this->addFlash('success', 'Page <strong>' . $pageName . '</strong> was removed correctly.');

				return $this->redirectToRoute('reaccion_cms_admin_pages_index');
			}
			catch(\Exception $e)
			{
				$this->addFlash('error', 'Error removing <strong>' . $pageName . '</strong> content: ' . $e->getMessage() . '.');

				return 	$this->redirectToRoute(
							'reaccion_cms_admin_pages_detail', 
							array('page' => $page->getId())
						);
			}
		}
	}