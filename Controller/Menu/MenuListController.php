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
			$em = $this->getDoctrine()->getManager();

			$dql =  "
					SELECT 
					m.id, p.id AS parent_id, m.name, m.type, m.target, m.position
					FROM  App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu m 
					LEFT JOIN App\ReaccionEstudio\ReaccionCMSBundle\Entity\Menu p 
					WITH p.id = m.parent
					";

			$query = $em->createQuery($dql);
			$menu = $query->getArrayResult();

			$nested = $this->get("reaccion_cms_admin.menu")->buildNestedArray($menu);

			return $this->render("@ReaccionCMSAdminBundle/menu/list.html.twig",
				[
					'menu' => $nested
				]
			);
		}
	}