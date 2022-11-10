<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\EntriesCategories;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use ReaccionEstudio\ReaccionCMSBundle\Entity\EntryCategory;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Contracts\Translation\TranslatorInterface;

class RemoveEntriesCategoryController extends AbstractController
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function index(EntryCategory $category, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if (empty($category)) {
            throw new NotFoundHttpException("Entries category item not found");
        }

        $name = $category->getName();

        try {
            // remove
            $em->remove($category);
            $em->flush();

            // flash message
            $this->addFlash('success', $this->translator->trans('entries_categories_form.remove_success_message', array('%name%' => $name)));
        } catch (\Exception $e) {
            $errorMssg = $this->translator->trans('entries_categories_form.remove_error_message', array('%name%' => $name, '%error%' => $e->getMessage()));
            $this->addFlash('error', $errorMssg);
        }

        return $this->redirectToRoute('reaccion_cms_admin_entries_categories_list');
    }
}
