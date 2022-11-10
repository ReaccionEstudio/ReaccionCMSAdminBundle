<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Menu;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Menu;

class MenuListController extends AbstractController
{
    /**
     * @var ParameterBagInterface
     */
    private $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $menu = $em->getRepository(Menu::class)->findBy(
            array(),
            array('id' => 'DESC')
        );

        // pagination
        $paginator = $this->get('knp_paginator');
        $menu = $paginator->paginate(
            $menu,
            $request->query->getInt('page', 1),
            $this->parameterBag->get("pagination_page_limit")
        );

        return $this->render("@ReaccionCMSAdminBundle/menu/list.html.twig",
            [
                'menu' => $menu
            ]
        );
    }
}
