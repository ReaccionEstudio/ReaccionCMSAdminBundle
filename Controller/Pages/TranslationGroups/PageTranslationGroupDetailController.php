<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Pages\TranslationGroups;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use ReaccionEstudio\ReaccionCMSBundle\Entity\PageTranslationGroup;
use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Pages\PageTranslationGroupType;
use Symfony\Contracts\Translation\TranslatorInterface;

class PageTranslationGroupDetailController extends AbstractController
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function index(PageTranslationGroup $pageTranslationGroup, Request $request)
    {
        // form
        $form = $this->createForm(PageTranslationGroupType::class, $pageTranslationGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // save
                $em = $this->getDoctrine()->getManager();
                $em->persist($pageTranslationGroup);
                $em->flush();

                $this->addFlash('success', $this->translator->trans('page_translation_group_form.update_success_message'));
                return $this->redirectToRoute('reaccion_cms_admin_pages_index');
            } catch (\Exception $e) {
                $errMssg = $this->translator->trans(
                    "page_translation_group_form.update_error_message",
                    array(
                        '%name%' => $form['name']->getData(),
                        '%error%' => $e->getMessage()
                    )
                );
                $this->addFlash('error', $errMssg);
            }
        }

        return $this->render("@ReaccionCMSAdminBundle/pages/translation_groups/form.html.twig",
            [
                'form' => $form->createView(),
                'mode' => 'edit',
                'pageTranslationGroup' => $pageTranslationGroup
            ]
        );
    }
}
