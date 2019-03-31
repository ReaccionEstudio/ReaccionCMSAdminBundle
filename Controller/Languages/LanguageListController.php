<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Languages;

use Symfony\Component\HttpFoundation\Request;
use ReaccionEstudio\ReaccionCMSBundle\Entity\Language;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LanguageListController extends Controller
{
    public function index(Request $request)
    {
        $languages = $this->get("reaccion_cms.manager.language")->findAll(['id' => 'DESC']);

        // pagination
        $paginator = $this->get('knp_paginator');
        $languages = $paginator->paginate(
            $languages,
            $request->query->getInt('page', 1),
            $this->getParameter("pagination_page_limit")
        );

        return $this->render("@ReaccionCMSAdminBundle/languages/list.html.twig",
            [
                'languages' => $languages
            ]
        );
    }
}