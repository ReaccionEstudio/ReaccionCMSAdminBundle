<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Languages;

use ReaccionEstudio\ReaccionCMSBundle\Entity\Language;
use ReaccionEstudio\ReaccionCMSBundle\Manager\LanguageManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Languages\LanguageType;
use Symfony\Component\Translation\TranslatorInterface;

class EditLanguageController extends Controller
{
    private $translator;

    private $languageManager;

    public function __construct(TranslatorInterface $translator, LanguageManager $languageManager)
    {
        $this->translator = $translator;
        $this->languageManager = $languageManager;
    }

    public function index(Language $language, Request $request)
    {
        $form = $this->createForm(LanguageType::class, $language);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            try
            {
                $this->languageManager->save($language);

                $this->addFlash('success', $this->translator->trans('languages_form.update_success_message') );

                return $this->redirectToRoute('reaccion_cms_admin_languages_index');
            }
            catch(\Exception $e)
            {
                $this->addFlash(
                    'error',
                    $this->translator->trans('languages_form.update_error_message', [
                        '%name%' => $form['username']->getData(),
                        '%error%' => $e->getMessage()
                    ])
                );
            }
        }

        return $this->render("@ReaccionCMSAdminBundle/languages/form.html.twig",
            [
                'form' => $form->createView(),
                'mode' => 'edit',
                'language' => $language
            ]
        );
    }
}