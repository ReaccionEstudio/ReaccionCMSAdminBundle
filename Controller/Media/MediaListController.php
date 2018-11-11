<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Media;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Media\ListMediaService;

	class MediaListController extends Controller
	{
		public function index(Request $request)
		{
			$listMediaService = new ListMediaService($this->getParameter("reaccion_cms_admin.upload_dir"));
			$media = $listMediaService->getList();
			
			return $this->render("@ReaccionCMSAdminBundle/media/list.html.twig",
				[
					'media' => $media
				]
			);
		}
	}