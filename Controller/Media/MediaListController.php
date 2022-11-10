<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Media;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Media;

class MediaListController extends AbstractController
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
        $queryFilters = [];

        if ($request->query->get('type')) {
            $queryFilters = ['type' => $request->query->get('type')];
        }

        $em = $this->getDoctrine()->getManager();
        $media = $em->getRepository(Media::class)->findBy(
            $queryFilters,
            array('id' => 'DESC')
        );

        // pagination
        $paginator = $this->get('knp_paginator');
        $media = $paginator->paginate(
            $media,
            $request->query->getInt('page', 1),
            $this->parameterBag->get("pagination_page_limit")
        );

        $template = "@ReaccionCMSAdminBundle/media/list.html.twig";

        if ($request->query->get('modal')) {
            $template = "@ReaccionCMSAdminBundle/media/modal_list.html.twig";
        }

        return $this->render($template,
            [
                'media' => $media,
            ]
        );
    }
}
