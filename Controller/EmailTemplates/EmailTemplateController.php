<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\EmailTemplates;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use ReaccionEstudio\ReaccionCMSBundle\Entity\EmailTemplate;

class EmailTemplateController extends AbstractController
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
        $emailTemplates = $em->getRepository(EmailTemplate::class)->findBy(
            array(),
            array('id' => 'DESC')
        );

        // pagination
        $paginator = $this->get('knp_paginator');
        $emailTemplates = $paginator->paginate(
            $emailTemplates,
            $request->query->getInt('page', 1),
            $this->parameterBag->get("pagination_page_limit")
        );

        return $this->render("@ReaccionCMSAdminBundle/emailTemplates/list.html.twig",
            [
                'emailTemplates' => $emailTemplates
            ]
        );
    }
}
