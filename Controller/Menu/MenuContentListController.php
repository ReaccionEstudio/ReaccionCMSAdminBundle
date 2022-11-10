<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Menu;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;

class MenuContentListController extends AbstractController
{
    public function index(Menu $menu, Request $request)
    {
        $nested = $this
            ->get("reaccion_cms.menu_content")
            ->buildNestedArray($menu, false);

        return $this->render("@ReaccionCMSAdminBundle/menu/content/list.html.twig",
            [
                'menuContent' => $nested,
                'menu' => $menu
            ]
        );
    }
}
