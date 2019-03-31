<?php

namespace ReaccionEstudio\ReaccionCMSAdminBundle\Controller\Languages;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use ReaccionEstudio\ReaccionCMSBundle\Manager\LanguageManager;
use ReaccionEstudio\ReaccionCMSAdminBundle\Form\Languages\LanguageType;

class CreateLanguageController extends Controller
{
    private $translator;

    private $languageManager;

    public function __construct(TranslatorInterface $translator, LanguageManager $languageManager)
    {
        $this->translator = $translator;
        $this->languageManager = $languageManager;
    }

    public function index(Request $request)
    {
        $language = $this->languageManager->create();

        // form
        $form = $this->createForm(LanguageType::class, $language);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            try
            {
                // save
                $this->languageManager->save($language);

                $this->addFlash('success', $this->translator->trans('languages_form.create_success_form') );

                return $this->redirectToRoute('reaccion_cms_admin_languages_index');
            }
            catch(\Exception $e)
            {
                $this->addFlash(
                    'error',
                    $this->translator->trans('languages_form.create_error_form', [
                        '%name%' => $form['name']->getData(),
                        '%error%' => $e->getMessage()
                    ])
                );
            }
        }

        return $this->render("@ReaccionCMSAdminBundle/languages/form.html.twig",
            [
                'form' => $form->createView(),
                'mode' => 'create'
            ]
        );
    }
}