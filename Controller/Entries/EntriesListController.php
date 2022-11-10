<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Entries;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Entry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntriesListController extends AbstractController
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
        $entries = $em->getRepository(Entry::class)->findBy(
            array(),
            array('id' => 'DESC')
        );

        // pagination
        $paginator = $this->get('knp_paginator');
        $entries = $paginator->paginate(
            $entries,
            $request->query->getInt('page', 1),
            $this->parameterBag->get("pagination_page_limit")
        );

        return $this->render("@ReaccionCMSAdminBundle/entries/list.html.twig",
            [
                'entries' => $entries
            ]
        );
    }
}
