<?php

	namespace App\ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Menu;

	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Bundle\FrameworkBundle\Controller\Controller;
	use Symfony\Component\Translation\TranslatorInterface;
	use App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;

	use App\ReaccionEstudio\ReaccionCMSAdminBundle\Services\Menu\MenuService;

	class MenuListController extends Controller
	{
		public function index(Request $request, TranslatorInterface $translator)
		{
			$nested = $this->get("reaccion_cms_admin.menu")->buildNestedArray(false);

			return $this->render("@ReaccionCMSAdminBundle/menu/list.html.twig",
				[
					'menu' => $nested
				]
			);
		}
	}