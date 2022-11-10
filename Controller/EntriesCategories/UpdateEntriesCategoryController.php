<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\EntriesCategories;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use ReaccionEstudio\ReaccionCMSBundle\Entity\EntryCategory;
use ReaccionEstudio\ReaccionCMSAdminBundle\Form\EntriesCategories\EntryCategoryType;
use Symfony\Contracts\Translation\TranslatorInterface;

class UpdateEntriesCategoryController extends AbstractController
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function index(EntryCategory $category, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        // form
        $form = $this->createForm(EntryCategoryType::class, $category, ['mode' => 'edit']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // generate slug
                $category->setSlug($category->getName());

                // save
                $em->persist($category);
                $em->flush();

                // success message
                $successMessage = $this->translator->trans('entries_categories_form.update_success_form');
                $this->addFlash('success', $successMessage);

                return $this->redirectToRoute('reaccion_cms_admin_entries_categories_list');
            } catch (\Exception $e) {
                $this->addFlash('error', $this->translator->trans('entries_categories_form.update_error_form', array('%error%' => $e->getMessage())));
            }
        }

        return $this->render("@ReaccionCMSAdminBundle/entries/categories/form.html.twig",
            [
                'form' => $form->createView(),
                'mode' => 'edit',
                'categoryName' => $form['name']->getData()
            ]
        );
    }
}
