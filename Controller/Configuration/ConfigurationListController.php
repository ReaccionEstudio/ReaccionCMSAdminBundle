<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Configuration;

use phpDocumentor\Reflection\DocBlock\Tags\Param;
use Symfony\Component\HttpFoundation\Request;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ConfigurationListController extends AbstractController
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
        $config = $em->getRepository(Configuration::class)->findBy(
            array(),
            array('id' => 'DESC')
        );

        // pagination
        $paginator = $this->get('knp_paginator');
        $config = $paginator->paginate(
            $config,
            $request->query->getInt('page', 1),
            $this->parameterBag->get("pagination_page_limit")
        );

        return $this->render("@ReaccionCMSAdminBundle/configuration/list.html.twig",
            [
                'config' => $config
            ]
        );
    }
}
