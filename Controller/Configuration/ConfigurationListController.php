<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Configuration;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Configuration;

class ConfigurationListController extends AbstractController
{
    public function index(Request $request, ParameterBagInterface $parameterBag)
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
            $parameterBag->get("pagination_page_limit")
        );

        return $this->render("@ReaccionCMSAdminBundle/configuration/list.html.twig",
            [
                'config' => $config
            ]
        );
    }
}
