<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Page;
use ReaccionEstudio\ReaccionCMSBundle\Entity\PageTranslationGroup;


class PageListController extends AbstractController
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
        $pages = $em->getRepository(Page::class)->findBy(
            array(),
            array('id' => 'DESC')
        );

        // pagination
        $paginator = $this->get('knp_paginator');
        $pages = $paginator->paginate(
            $pages,
            $request->query->getInt('page', 1),
            $this->parameterBag->get("pagination_page_limit")
        );

        // get translation groups
        $translationGroups = $em->getRepository(PageTranslationGroup::class)->findBy([], ['id' => 'DESC']);

        return $this->render("@ReaccionCMSAdminBundle/pages/list.html.twig",
            [
                'pages' => $pages,
                'translationGroups' => $translationGroups
            ]
        );
    }
}
