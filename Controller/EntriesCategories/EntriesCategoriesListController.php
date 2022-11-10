<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\EntriesCategories;

use Symfony\Component\HttpFoundation\Request;
use ReaccionEstudio\ReaccionCMSBundle\Entity\EntryCategory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntriesCategoriesListController extends AbstractController
{
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository(EntryCategory::class)->findBy(
            array(),
            array('id' => 'DESC')
        );

        // pagination
        $paginator = $this->get('knp_paginator');
        $categories = $paginator->paginate(
            $categories,
            $request->query->getInt('page', 1),
            $this->getParameter("pagination_page_limit")
        );

        return $this->render("@ReaccionCMSAdminBundle/entries/categories/list.html.twig",
            [
                'categories' => $categories
            ]
        );
    }
}
